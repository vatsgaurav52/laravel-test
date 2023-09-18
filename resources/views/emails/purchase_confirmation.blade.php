<!DOCTYPE html>
<html>
<head>
    <title>Purchase Confirmation</title>
</head>
<body>
    <h1>Purchase Confirmation</h1>
    <p>Dear {{ $data['user']['name'] }},</p>
    <p>Thank you for your purchase. You have successfully purchased the product(s).</p>
    <p>Product: {{ $data['productType']}}</p>
    <p>If you have any questions or need further assistance, please contact our support team.</p>
    <p>Best regards,<br>Exotica</p>
</body>
</html>