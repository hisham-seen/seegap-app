<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

return [
    // Payments
    'payments.title' => 'Payments',
    'payments.header' => 'Payments',
    'payments.menu' => 'Payments',
    'payments.breadcrumb' => 'Payments',
    'payments.subheader' => 'View your payment history',
    'payments.no_data' => 'No payments found',
    'payments.no_data_help' => 'Your payment history will appear here.',

    // Payment details
    'payments.payment_id' => 'Payment ID',
    'payments.amount' => 'Amount',
    'payments.currency' => 'Currency',
    'payments.status' => 'Status',
    'payments.date' => 'Date',
    'payments.method' => 'Payment method',
    'payments.processor' => 'Processor',
    'payments.plan' => 'Plan',
    'payments.billing_cycle' => 'Billing cycle',
    'payments.invoice' => 'Invoice',
    'payments.receipt' => 'Receipt',

    // Payment status
    'payments.status.completed' => 'Completed',
    'payments.status.pending' => 'Pending',
    'payments.status.failed' => 'Failed',
    'payments.status.cancelled' => 'Cancelled',
    'payments.status.refunded' => 'Refunded',
    'payments.status.partially_refunded' => 'Partially refunded',

    // Payment methods
    'payments.method.credit_card' => 'Credit card',
    'payments.method.debit_card' => 'Debit card',
    'payments.method.paypal' => 'PayPal',
    'payments.method.stripe' => 'Stripe',
    'payments.method.bank_transfer' => 'Bank transfer',
    'payments.method.crypto' => 'Cryptocurrency',
    'payments.method.other' => 'Other',

    // Payment processors
    'payments.processor.stripe' => 'Stripe',
    'payments.processor.paypal' => 'PayPal',
    'payments.processor.paddle' => 'Paddle',
    'payments.processor.razorpay' => 'Razorpay',
    'payments.processor.paystack' => 'Paystack',
    'payments.processor.mollie' => 'Mollie',
    'payments.processor.coinbase' => 'Coinbase',
    'payments.processor.flutterwave' => 'Flutterwave',
    'payments.processor.mercadopago' => 'MercadoPago',
    'payments.processor.yookassa' => 'YooKassa',
    'payments.processor.payu' => 'PayU',
    'payments.processor.midtrans' => 'Midtrans',
    'payments.processor.iyzico' => 'Iyzico',
    'payments.processor.myfatoorah' => 'MyFatoorah',
    'payments.processor.crypto_com' => 'Crypto.com',
    'payments.processor.lemonsqueezy' => 'Lemon Squeezy',

    // Payment actions
    'payments.view' => 'View payment',
    'payments.download_invoice' => 'Download invoice',
    'payments.download_receipt' => 'Download receipt',
    'payments.request_refund' => 'Request refund',
    'payments.dispute' => 'Dispute payment',
    'payments.retry' => 'Retry payment',

    // Payment filters
    'payments.filter.all' => 'All payments',
    'payments.filter.completed' => 'Completed',
    'payments.filter.pending' => 'Pending',
    'payments.filter.failed' => 'Failed',
    'payments.filter.refunded' => 'Refunded',
    'payments.filter.date_range' => 'Date range',
    'payments.filter.amount_range' => 'Amount range',
    'payments.filter.payment_method' => 'Payment method',
    'payments.filter.processor' => 'Processor',

    // Payment summary
    'payments.summary' => 'Payment summary',
    'payments.summary.total_paid' => 'Total paid',
    'payments.summary.total_refunded' => 'Total refunded',
    'payments.summary.successful_payments' => 'Successful payments',
    'payments.summary.failed_payments' => 'Failed payments',
    'payments.summary.average_payment' => 'Average payment',
    'payments.summary.this_month' => 'This month',
    'payments.summary.last_month' => 'Last month',
    'payments.summary.this_year' => 'This year',

    // Billing information
    'payments.billing' => 'Billing information',
    'payments.billing.name' => 'Billing name',
    'payments.billing.email' => 'Billing email',
    'payments.billing.address' => 'Billing address',
    'payments.billing.city' => 'City',
    'payments.billing.state' => 'State',
    'payments.billing.country' => 'Country',
    'payments.billing.postal_code' => 'Postal code',
    'payments.billing.tax_id' => 'Tax ID',
    'payments.billing.update' => 'Update billing information',

    // Payment methods management
    'payments.payment_methods' => 'Payment methods',
    'payments.payment_methods.add' => 'Add payment method',
    'payments.payment_methods.default' => 'Default',
    'payments.payment_methods.set_default' => 'Set as default',
    'payments.payment_methods.remove' => 'Remove',
    'payments.payment_methods.expired' => 'Expired',
    'payments.payment_methods.expires' => 'Expires %s',
    'payments.payment_methods.ending_in' => 'Ending in %s',

    // Invoices
    'payments.invoices' => 'Invoices',
    'payments.invoices.invoice_number' => 'Invoice #%s',
    'payments.invoices.due_date' => 'Due date',
    'payments.invoices.paid_date' => 'Paid date',
    'payments.invoices.subtotal' => 'Subtotal',
    'payments.invoices.tax' => 'Tax',
    'payments.invoices.total' => 'Total',
    'payments.invoices.download' => 'Download invoice',
    'payments.invoices.send_email' => 'Send via email',

    // Refunds
    'payments.refunds' => 'Refunds',
    'payments.refunds.request' => 'Request refund',
    'payments.refunds.reason' => 'Refund reason',
    'payments.refunds.reason_placeholder' => 'Please explain why you are requesting a refund...',
    'payments.refunds.amount' => 'Refund amount',
    'payments.refunds.partial' => 'Partial refund',
    'payments.refunds.full' => 'Full refund',
    'payments.refunds.submit' => 'Submit refund request',
    'payments.refunds.status.pending' => 'Refund pending',
    'payments.refunds.status.approved' => 'Refund approved',
    'payments.refunds.status.denied' => 'Refund denied',
    'payments.refunds.status.processed' => 'Refund processed',

    // Tax information
    'payments.tax' => 'Tax information',
    'payments.tax.rate' => 'Tax rate',
    'payments.tax.amount' => 'Tax amount',
    'payments.tax.inclusive' => 'Tax inclusive',
    'payments.tax.exclusive' => 'Tax exclusive',
    'payments.tax.exempt' => 'Tax exempt',
    'payments.tax.vat_number' => 'VAT number',
    'payments.tax.tax_id' => 'Tax ID',

    // Subscription management
    'payments.subscription' => 'Subscription',
    'payments.subscription.active' => 'Active subscription',
    'payments.subscription.cancelled' => 'Cancelled subscription',
    'payments.subscription.expired' => 'Expired subscription',
    'payments.subscription.next_payment' => 'Next payment',
    'payments.subscription.cancel' => 'Cancel subscription',
    'payments.subscription.reactivate' => 'Reactivate subscription',
    'payments.subscription.change_plan' => 'Change plan',
    'payments.subscription.update_payment_method' => 'Update payment method',

    // Payment errors
    'payments.error.payment_failed' => 'Payment failed. Please try again.',
    'payments.error.invalid_payment_method' => 'Invalid payment method.',
    'payments.error.insufficient_funds' => 'Insufficient funds.',
    'payments.error.card_declined' => 'Card was declined.',
    'payments.error.expired_card' => 'Card has expired.',
    'payments.error.invalid_card' => 'Invalid card details.',
    'payments.error.processing_error' => 'Payment processing error.',
    'payments.error.refund_failed' => 'Refund request failed.',
    'payments.error.invoice_not_found' => 'Invoice not found.',
    'payments.error.payment_not_found' => 'Payment not found.',

    // Payment success messages
    'payments.success.payment_completed' => 'Payment completed successfully.',
    'payments.success.refund_requested' => 'Refund request submitted successfully.',
    'payments.success.payment_method_added' => 'Payment method added successfully.',
    'payments.success.payment_method_removed' => 'Payment method removed successfully.',
    'payments.success.billing_updated' => 'Billing information updated successfully.',
    'payments.success.subscription_cancelled' => 'Subscription cancelled successfully.',
    'payments.success.subscription_reactivated' => 'Subscription reactivated successfully.',

    // Payment notifications
    'payments.notifications.payment_received' => 'Payment received',
    'payments.notifications.payment_failed' => 'Payment failed',
    'payments.notifications.refund_processed' => 'Refund processed',
    'payments.notifications.subscription_cancelled' => 'Subscription cancelled',
    'payments.notifications.card_expiring' => 'Card expiring soon',
    'payments.notifications.payment_retry' => 'Payment retry scheduled',

    // Export
    'payments.export' => 'Export payments',
    'payments.export.csv' => 'Export as CSV',
    'payments.export.pdf' => 'Export as PDF',
    'payments.export.excel' => 'Export as Excel',
    'payments.export.date_range' => 'Select date range',
    'payments.export.all_payments' => 'All payments',
    'payments.export.successful_only' => 'Successful payments only',

    // Custom Plan Payment Processors
    'pay.custom_plan.paypal' => 'PayPal Custom Plan',
    'pay.custom_plan.stripe' => 'Stripe Custom Plan',
    'pay.custom_plan.offline_payment' => 'Offline Payment Custom Plan',
    'pay.custom_plan.coinbase' => 'Coinbase Custom Plan',
    'pay.custom_plan.payu' => 'PayU Custom Plan',
    'pay.custom_plan.iyzico' => 'Iyzico Custom Plan',
    'pay.custom_plan.paystack' => 'Paystack Custom Plan',
    'pay.custom_plan.razorpay' => 'Razorpay Custom Plan',
    'pay.custom_plan.mollie' => 'Mollie Custom Plan',
    'pay.custom_plan.yookassa' => 'YooKassa Custom Plan',
    'pay.custom_plan.crypto_com' => 'Crypto.com Custom Plan',
    'pay.custom_plan.paddle' => 'Paddle Custom Plan',
    'pay.custom_plan.mercadopago' => 'MercadoPago Custom Plan',
    'pay.custom_plan.midtrans' => 'Midtrans Custom Plan',
    'pay.custom_plan.flutterwave' => 'Flutterwave Custom Plan',
    'pay.custom_plan.lemonsqueezy' => 'Lemon Squeezy Custom Plan',
    'pay.custom_plan.myfatoorah' => 'MyFatoorah Custom Plan',
];
