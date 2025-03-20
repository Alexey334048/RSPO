<?php
$amount = isset($_GET['amount']) ? (float)$_GET['amount'] : 0;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Payment.css">
    <title>Document</title>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cardNumberInput = document.getElementById("card-number");
            const expiryDateInput = document.getElementById("expiry-date");

            cardNumberInput.addEventListener("input", function(e) {
                let value = e.target.value.replace(/\D/g, ""); // Удаляем все нецифровые символы
                value = value.replace(/(.{4})/g, "$1 ").trim(); // Разделяем по 4 цифры
                e.target.value = value;
            });

            expiryDateInput.addEventListener("input", function(e) {
                let value = e.target.value.replace(/\D/g, ""); // Удаляем все нецифровые символы
                if (value.length > 2) {
                    value = value.replace(/(\d{2})(\d{1,2})/, "$1/$2"); // Разделяем MM/YY
                }
                e.target.value = value;
            });
        });
    </script>
</head>

<body>
    <div class="payment-container">
        <h1>Payment Details</h1>
        <form class="payment-form">
            <div class="form-group">
                <label for="cardholder-name">Имя владельца карты</label>
                <input type="text" id="cardholder-name" placeholder="John Doe" required>
            </div>

            <div class="form-group">
                <label for="card-number">Номер карты</label>
                <input type="text" id="card-number" placeholder="1234 5678 9012 3456" maxlength="19" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="expiry-date">Срок действия</label>
                    <input type="text" id="expiry-date" placeholder="MM/YY" maxlength="5" required>
                </div>
                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="password" id="cvv" placeholder="123" maxlength="3" required>
                </div>
            </div>

            <div class="form-group">
                <label for="amount">Сумма</label>
                <input type="text" id="amount" name="amount" value="<?= $amount ?>" readonly>
            </div>

            <button type="button" class="pay-button" onclick="window.location.href='index.php'">Оплатить</button>

        </form>
    </div>
    <script>
    function redirectToPayment() {
        let totalPrice = document.getElementById('total_price_input').value;
        let payButton = document.getElementById('pay-button');
        payButton.href = "payment.php?amount=" + totalPrice;
    }
</script>
</body>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #1e1e1e;
        color: #f5f5f5;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    
    .payment-container {
        background-color: #333;
        padding: 30px;
        border-radius: 10px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }
    
    .payment-container h1 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 24px;
    }
    
    .payment-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-group label {
        margin-bottom: 5px;
        font-size: 14px;
        color: #ccc;
    }
    
    .form-group input {
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #444;
        color: #fff;
    }
    
    .form-group input::placeholder {
        color: #aaa;
    }
    
    .form-row {
        display: flex;
        gap: 10px;
    }
    
    .form-row .form-group {
        flex: 1;
    }
    
    .pay-button {
        padding: 10px;
        background-color: #4caf50;
        color: #fff;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    
    .pay-button:hover {
        background-color: #45a049;
    }
</style>

</html>