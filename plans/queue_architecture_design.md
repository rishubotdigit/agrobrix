# Queue Architecture Design for Emails and Notifications

## Overview
This design implements asynchronous email and notification sending using Laravel's queue system with database driver to prevent blocking HTTP requests. The architecture includes job classes for individual and bulk email sending, with built-in retry mechanisms and error handling.

## Current State Analysis
- Laravel 12 project with existing email listeners that send synchronously
- Queue configuration already set to database driver
- Existing listeners: SendInvoiceEmail, SendPaymentApprovedEmail, NotifyAdminsOfPaymentApproved, etc.
- Email logging via EmailLog model
- Dynamic SMTP settings via trait

## Proposed Architecture

### 1. Job Classes

#### SendEmailJob
- **Purpose**: Handles sending individual emails asynchronously
- **Parameters**: 
  - Mailable class instance
  - Recipient email
  - User ID (for logging)
  - Model type/ID (for logging)
  - Email type (e.g., 'invoice', 'payment_approved')
- **Features**:
  - Loads dynamic SMTP settings
  - Sends email with try/catch
  - Logs success/failure to EmailLog
  - Implements retry logic (3 attempts, exponential backoff)

#### SendBulkEmailJob
- **Purpose**: Handles sending emails to multiple recipients
- **Parameters**:
  - Mailable class instance
  - Array of recipients (email, user_id, model info)
  - Email type
- **Features**:
  - Processes recipients in batches of 10
  - Individual error handling per recipient
  - Continues processing even if some emails fail
  - Logs each email attempt

### 2. Modified Listeners

#### Individual Email Listeners (e.g., SendInvoiceEmail)
- Remove direct Mail::send() calls
- Dispatch SendEmailJob instead
- Pass required parameters to job

#### Bulk Email Listeners (e.g., NotifyAdminsOfPaymentApproved)
- Remove foreach loops with Mail::send()
- Collect recipient data
- Dispatch SendBulkEmailJob with recipient array

### 3. Queue Configuration
- **Driver**: Database (already configured)
- **Retry After**: 90 seconds (default)
- **Failed Jobs**: Database-uuids driver (already configured)
- **Worker Configuration**:
  - Multiple workers for scalability
  - Separate queues for high/low priority emails
  - Timeout settings for long-running jobs

### 4. Performance Considerations
- **Batching**: SendBulkEmailJob processes in batches to avoid memory issues
- **Prioritization**: Use separate queues (e.g., 'emails', 'notifications') with different priorities
- **Rate Limiting**: Implement delays between batches if needed
- **Memory Management**: Clear large objects after processing

### 5. Error Handling & Retries
- **Job Retries**: 3 attempts with exponential backoff
- **Failed Jobs Table**: Automatic logging of failed jobs
- **Email Logging**: Continue existing EmailLog pattern within jobs
- **Exception Handling**: Catch and log exceptions without failing entire job
- **Dead Letter Queue**: Failed jobs can be inspected and re-queued manually

### 6. Scalability Features
- **Horizontal Scaling**: Multiple queue workers across servers
- **Queue Monitoring**: Use Laravel Horizon or custom monitoring
- **Load Balancing**: Distribute jobs across multiple workers
- **Database Optimization**: Ensure jobs table is indexed properly

### 7. Implementation Steps
1. ✅ Create app/Jobs directory
2. ✅ Create SendEmailJob and SendBulkEmailJob classes
3. ✅ Modify existing listeners to dispatch jobs
4. ✅ Test queue worker setup
5. ✅ Monitor and optimize performance
6. ✅ Add monitoring/alerting for failed jobs

## Final Implementation Details

### Job Classes Created
- `app/Jobs/SendEmailJob.php`: Handles individual emails with 3 retries and exponential backoff
- `app/Jobs/SendBulkEmailJob.php`: Handles bulk emails with batching (10 recipients per batch)

### Modified Listeners
**Individual Email Listeners:**
- SendInvoiceEmail
- SendPaymentApprovedEmail
- CreateNotificationForPlanPurchaseCreated (user email)
- CreateNotificationForUserRegistered (welcome email)
- CreateNotificationForPropertyApproved
- CreateNotificationForPropertyRejected

**Bulk Email Listeners:**
- NotifyAdminsOfPaymentApproved
- NotifyAdminsOfPropertyApproved
- CreateNotificationForPaymentSubmittedForApproval
- CreateNotificationForPropertySubmittedForApproval
- CreateNotificationForPlanPurchaseCreated (admin email)
- CreateNotificationForUserRegistered (admin email)

### Queue Configuration
- Added 'emails' and 'notifications' queues
- SendEmailJob uses 'emails' queue
- SendBulkEmailJob uses 'notifications' queue
- Retry after: 90 seconds
- Failed jobs stored in database-uuids driver

### Worker Commands
```bash
# Run queue workers
php artisan queue:work --queue=emails,notifications --tries=3 --timeout=90 --sleep=3 --max-jobs=1000

# Monitor failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

### Monitoring
- Failed jobs are automatically logged to `failed_jobs` table
- Email sending attempts logged to `email_logs` table
- Job failures logged with detailed error messages
- Queue worker can be monitored via Laravel's logging system

### 8. Migration Path
- Existing synchronous emails continue working during transition
- Gradual rollout: update listeners one by one
- Backward compatibility maintained
- Rollback plan: revert listeners if issues arise

## Benefits
- Non-blocking HTTP requests
- Improved user experience
- Better error resilience
- Scalable email processing
- Centralized email logging
- Easy monitoring and debugging

## Potential Challenges
- Job serialization issues with complex objects
- Memory usage with large recipient lists
- SMTP connection limits
- Email deliverability (spam filters)
- Monitoring queue health

## Monitoring & Maintenance
- Queue status commands: `php artisan queue:status`
- Failed jobs: `php artisan queue:failed`
- Worker management: `php artisan queue:work`
- Horizon dashboard for advanced monitoring