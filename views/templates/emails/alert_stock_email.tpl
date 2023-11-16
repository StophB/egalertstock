{if isset($product_name) && isset($customer_name)}
    <p>Hello {$customer_name},</p>
    <p>We are pleased to inform you that the product '{$product_name}' is now back in stock!</p>
    <p>Thank you for your interest.</p>
    <p>Best regards,</p>
    <p>Your Shop Team</p>
{else}
    <p>Invalid email template variables.</p>
{/if}
