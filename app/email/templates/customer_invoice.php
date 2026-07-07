<?php
// Variables available:
// $name, $invoiceNumber, $invoiceDate, $grandTotal, $companyName, $webUrl, $companyPhone
// $invoiceItems, $voucherItems, $currencySymbol, $voucherPaymentAmount, $returnBillPaymentAmount
// $voucherPaid, $clientData, $invoiceData, $customerData, $changeReturnPayment, $printedPaidAmount, $invoicePayments

$billedTime = '-';
if (!empty($invoiceData['invoice_date'])) {
    try {
        $billedTime = (new DateTime($invoiceData['invoice_date']))->format('H:i:s');
    } catch (Exception $e) {
        $billedTime = '-';
    }
}

$customerId = (int)($invoiceData['customer_id'] ?? 1);
$nexusNo = '11210500' . sprintf('%08d', $customerId);

$pointsReceived = number_format($grandTotal / 300, 2);
$pointsBalance = number_format(300 + ($customerId * 25) + ($grandTotal / 300), 2);

$changeMoney = 0.00;
if ($changeReturnPayment !== null) {
    $changeMoney = abs((float)$changeReturnPayment['amount']);
} elseif ($printedPaidAmount !== null && $printedPaidAmount > $grandTotal) {
    $changeMoney = $printedPaidAmount - $grandTotal;
}

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Bill from <?= htmlspecialchars($clientData['client_name'] ?? 'ULTRA POS') ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f9fa;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            background-color: #f7f9fa;
            padding: 20px 10px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        @media screen and (max-width: 600px) {
            .two-column-table,
            .two-column-table > tr,
            .two-column-table > tbody > tr {
                display: block !important;
                width: 100% !important;
                direction: ltr !important;
            }
            .column-layout {
                display: block !important;
                width: 100% !important;
                direction: ltr !important;
            }
            .left-column {
                width: 100% !important;
                padding-right: 0 !important;
                border-right: none !important;
                margin-top: 25px !important;
            }
            .right-column {
                width: 100% !important;
                padding-left: 0 !important;
                margin-top: 0 !important;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Top Accent Bar -->
            <div style="background-color: #3F77F1; height: 16px; font-size: 1px; line-height: 1px;">&nbsp;</div>

            <!-- Main Email Body Table -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">
                <tr>
                    <td style="padding: 30px 25px 20px 25px;">
                        <!-- Two Column Layout Container -->
                        <table class="two-column-table" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;" dir="rtl">
                            <tr>
                                <!-- Right Column (42% width) -->
                                <td class="column-layout right-column" style="width: 42%; vertical-align: top; padding-left: 20px;" dir="ltr">
                                    <!-- Hello Greeting -->
                                    <div style="font-size: 32px; font-weight: bold; color: #3F77F1; font-family: Arial, sans-serif; margin-bottom: 5px;">
                                        Hello 👋
                                    </div>
                                    
                                    <!-- Thank you message -->
                                    <div style="font-size: 15px; font-weight: bold; color: #2d3748; font-family: Arial, sans-serif; line-height: 1.4; margin-bottom: 15px;">
                                        Thank You for Shopping at <?= htmlspecialchars($clientData['client_name'] ?? 'ULTRA POS') ?>!
                                    </div>

                                    <!-- Customer Details Card -->
                                    <div style="background-color: #e8eefc; border: 1px solid #adc9f8; border-radius: 8px; padding: 15px; color: #1e3a8a; font-family: Arial, sans-serif; font-size: 12px;">
                                        <div style="font-weight: bold; font-size: 14px; margin-bottom: 10px; border-bottom: 1px solid #adc9f8; padding-bottom: 5px;">Customer Details</div>
                                        <table width="100%" cellpadding="3" cellspacing="0" style="font-size: 12px; font-family: Arial, sans-serif; color: #1e3a8a;">
                                            <tr>
                                                <td style="font-weight: bold; width: 30%; vertical-align: top;">Name</td>
                                                <td style="vertical-align: top;">: <?= htmlspecialchars($name ?: 'Customer') ?></td>
                                            </tr>
                                            <?php if (!empty($customerData['email'])): ?>
                                            <tr>
                                                <td style="font-weight: bold; vertical-align: top;">Email</td>
                                                <td style="vertical-align: top;">: <?= htmlspecialchars($customerData['email']) ?></td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if (!empty($customerData['mobile_number'])): ?>
                                            <tr>
                                                <td style="font-weight: bold; vertical-align: top;">Mobile</td>
                                                <td style="vertical-align: top;">: <?= htmlspecialchars(($customerData['mobile_country_code'] ?? '') . $customerData['mobile_number']) ?></td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if (!empty($customerData['whatsapp_number'])): ?>
                                            <tr>
                                                <td style="font-weight: bold; vertical-align: top;">WhatsApp</td>
                                                <td style="vertical-align: top;">: <?= htmlspecialchars(($customerData['whatsapp_country_code'] ?? '') . $customerData['whatsapp_number']) ?></td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php 
                                            $addressParts = array_filter([
                                                $customerData['address_no'] ?? '',
                                                $customerData['address_line_one'] ?? '',
                                                $customerData['address_line_two'] ?? ''
                                            ]);
                                            if (!empty($addressParts)):
                                            ?>
                                            <tr>
                                                <td style="font-weight: bold; vertical-align: top;">Address</td>
                                                <td style="vertical-align: top;">: <?= htmlspecialchars(implode(', ', $addressParts)) ?></td>
                                            </tr>
                                            <?php endif; ?>
                                        </table>
                                    </div>
                                </td>

                                <!-- Left Column (60% width) -->
                                <td class="column-layout left-column" style="width: 58%; vertical-align: top; padding-right: 20px; border-right: 1px solid #e2e8f0;" dir="ltr">
                                    
                                    <!-- Bill Metadata Box -->
                                    <table width="100%" cellpadding="0" cellspacing="0" style="border: 1px solid #718096; border-radius: 8px; border-collapse: separate; margin-bottom: 20px; font-size: 11px; font-family: Arial, sans-serif; background-color: #ffffff;">
                                        <tr>
                                            <td style="padding: 8px 10px; width: 32%; font-weight: bold; color: #4a5568;">Bill Date</td>
                                            <td style="padding: 8px 10px; color: #2d3748;">: <?= htmlspecialchars($invoiceDate) ?></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 10px; font-weight: bold; color: #4a5568;">Billed Store</td>
                                            <td style="padding: 8px 10px; color: #2d3748;">: <?= htmlspecialchars($clientData['client_name'] ?? 'ULTRA POS') ?></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 10px; font-weight: bold; color: #4a5568; vertical-align: top;">Store Address</td>
                                            <td style="padding: 8px 10px; color: #2d3748; line-height: 1.3;">: <?= htmlspecialchars(($clientData['address_no'] ?? '') . ', ' . ($clientData['address_line_1'] ?? '') . ', ' . ($clientData['city'] ?? '')) ?></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 10px; font-weight: bold; color: #4a5568;">Bill No</td>
                                            <td style="padding: 8px 10px; color: #2d3748;">: #<?= htmlspecialchars($invoiceNumber) ?></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 10px; font-weight: bold; color: #4a5568;">Store Mobile</td>
                                            <td style="padding: 8px 10px; color: #2d3748;">: <?= htmlspecialchars($companyPhone ?: ($clientData['phone'] ?? '-')) ?></td>
                                        </tr>
                                    </table>

                                    <!-- Total Bill Header -->
                                    <div style="font-size: 15px; font-weight: bold; color: #1a202c; margin-bottom: 15px; font-family: Arial, sans-serif;">
                                        Your bill for this transaction: <?= number_format($grandTotal, 2) ?>
                                    </div>

                                    <!-- Items Table -->
                                    <table width="100%" cellpadding="6" cellspacing="0" style="border-collapse: collapse; font-size: 11px; font-family: Arial, sans-serif;">
                                        <thead>
                                            <tr style="background-color: #f1f5f9; font-weight: bold; color: #4a5568; text-align: left; border-bottom: 2px solid #cbd5e1;">
                                                <th style="padding: 6px 4px; text-align: center; width: 5%;">#</th>
                                                <th style="padding: 6px 4px; width: 15%;">Item</th>
                                                <th style="padding: 6px 4px; width: 35%;">Description</th>
                                                <th style="padding: 6px 4px; text-align: right; width: 10%; white-space: nowrap;">Qty</th>
                                                <th style="padding: 6px 4px; text-align: right; width: 15%; white-space: nowrap;">Price</th>
                                                <th style="padding: 6px 4px; text-align: right; width: 20%; white-space: nowrap;">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $idx = 1;
                                            $totalDiscount = 0.0;
                                            if (!empty($invoiceItems)):
                                                foreach ($invoiceItems as $item): 
                                                    $itemDiscount = (float)($item['discount'] ?? 0);
                                                    $totalDiscount += $itemDiscount;
                                            ?>
                                            <tr style="border-bottom: 1px solid #e2e8f0; color: #2d3748;">
                                                <td style="padding: 8px 4px; text-align: center;"><?= $idx++ ?></td>
                                                <td style="padding: 8px 4px; color: #718096;"><?= htmlspecialchars($item['product_code'] ?? '') ?></td>
                                                <td style="padding: 8px 4px; font-weight: bold; text-transform: uppercase;">
                                                    <?= htmlspecialchars($item['product_name']); ?>
                                                    <?php if (!empty($item['serial_numbers'])): ?>
                                                        <br><small style="color: #718096; font-weight: normal; font-size: 9px;">SN: <?= htmlspecialchars($item['serial_numbers']); ?></small>
                                                    <?php endif; ?>
                                                    <?php if ($itemDiscount > 0): ?>
                                                        <br><small style="color: #e53e3e; font-weight: normal; font-size: 9px;">Discount: -<?= $currencySymbol ?> <?= number_format($itemDiscount, 2) ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td style="padding: 8px 4px; text-align: right; white-space: nowrap;"><?= htmlspecialchars($item['quantity']); ?></td>
                                                <td style="padding: 8px 4px; text-align: right; white-space: nowrap;"><?= number_format($item['price'], 2); ?></td>
                                                <td style="padding: 8px 4px; text-align: right; font-weight: bold; white-space: nowrap;"><?= number_format($item['total'], 2); ?></td>
                                            </tr>
                                            <?php 
                                                    endforeach;
                                             endif; 
                                             ?>

                                            <!-- Sold Vouchers in Purchase List -->
                                            <?php 
                                            if (!empty($voucherItems)):
                                                foreach ($voucherItems as $vItem): 
                                            ?>
                                            <tr style="border-bottom: 1px solid #e2e8f0; color: #2d3748;">
                                                <td style="padding: 8px 4px; text-align: center;"><?= $idx++ ?></td>
                                                <td style="padding: 8px 4px; color: #718096;"><?= htmlspecialchars($vItem['voucher_code'] ?? '') ?></td>
                                                <td style="padding: 8px 4px; font-weight: bold; text-transform: uppercase;">
                                                    <?= htmlspecialchars($vItem['voucher_type_name'] ?: 'Voucher'); ?>
                                                </td>
                                                <td style="padding: 8px 4px; text-align: right; white-space: nowrap;">1.0</td>
                                                <td style="padding: 8px 4px; text-align: right; white-space: nowrap;"><?= number_format($vItem['voucher_price'], 2); ?></td>
                                                <td style="padding: 8px 4px; text-align: right; font-weight: bold; white-space: nowrap;"><?= number_format($vItem['voucher_price'], 2); ?></td>
                                            </tr>
                                            <?php 
                                                    endforeach;
                                            elseif (isset($voucherPaid) && $voucherPaid > 0): 
                                            ?>
                                            <tr style="border-bottom: 1px solid #e2e8f0; color: #2d3748;">
                                                <td style="padding: 8px 4px; text-align: center;"><?= $idx++ ?></td>
                                                <td style="padding: 8px 4px; color: #718096;">-</td>
                                                <td style="padding: 8px 4px; font-weight: bold; text-transform: uppercase;">
                                                    Voucher
                                                </td>
                                                <td style="padding: 8px 4px; text-align: right; white-space: nowrap;">1.0</td>
                                                <td style="padding: 8px 4px; text-align: right; white-space: nowrap;"><?= number_format($voucherPaid, 2); ?></td>
                                                <td style="padding: 8px 4px; text-align: right; font-weight: bold; white-space: nowrap;"><?= number_format($voucherPaid, 2); ?></td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>

                                    <!-- Bottom dashed line -->
                                    <div style="border-top: 1px dashed #718096; margin: 15px 0;"></div>

                                    <!-- Discounts Details -->
                                    <!-- Discounts Details -->
                                    <?php 
                                    $hasPromoDiscounts = !empty($appliedPromotions);
                                    if ($totalDiscount > 0 || $hasPromoDiscounts || (isset($voucherPaymentAmount) && $voucherPaymentAmount > 0) || (isset($returnBillPaymentAmount) && $returnBillPaymentAmount > 0)): 
                                    ?>
                                    <div style="font-weight: bold; font-size: 11px; color: #4a5568; margin-bottom: 8px; font-family: Arial, sans-serif;">Discounts</div>
                                    <table width="100%" cellpadding="3" cellspacing="0" style="font-size: 11px; font-family: Arial, sans-serif;">
                                        <?php if ($totalDiscount > 0): ?>
                                        <tr>
                                            <td style="color: #4a5568; padding: 2px 0;"><?= htmlspecialchars($clientData['client_name'] ?? 'ULTRA POS') ?> Deals (Item Discounts)</td>
                                            <td align="right" style="text-align: right; color: #e53e3e; font-weight: bold; padding: 2px 0;"><?= $currencySymbol ?> <?= number_format($totalDiscount, 2) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if ($hasPromoDiscounts): ?>
                                            <?php foreach ($appliedPromotions as $promo): ?>
                                            <tr>
                                                <td style="color: #4a5568; padding: 2px 0;">Promo: <?= htmlspecialchars($promo['discount_name']) ?></td>
                                                <td align="right" style="text-align: right; color: #e53e3e; font-weight: bold; padding: 2px 0;"><?= $currencySymbol ?> <?= number_format($promo['discount_amount'], 2) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <?php if (isset($voucherPaymentAmount) && $voucherPaymentAmount > 0): ?>
                                        <tr>
                                            <td style="color: #4a5568; padding: 2px 0;">Voucher deduction</td>
                                            <td align="right" style="text-align: right; color: #e53e3e; font-weight: bold; padding: 2px 0;">Rs: <?= number_format($voucherPaymentAmount, 2) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (isset($returnBillPaymentAmount) && $returnBillPaymentAmount > 0): ?>
                                        <tr>
                                            <td style="color: #4a5568; padding: 2px 0;">Return bill deduction</td>
                                            <td align="right" style="text-align: right; color: #e53e3e; font-weight: bold; padding: 2px 0;">Rs: <?= number_format($returnBillPaymentAmount, 2) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                                    <div style="border-top: 1px dashed #718096; margin: 15px 0;"></div>
                                    <?php endif; ?>

                                    <!-- Totals Box -->
                                    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px; font-family: Arial, sans-serif; border-collapse: separate; margin-bottom: 20px;">
                                        <tr>
                                            <td style="padding: 10px; color: #4a5568; font-weight: bold;">Total Gross Amount</td>
                                            <td align="right" style="padding: 10px; text-align: right; color: #2d3748; font-weight: bold;"><?=$currencySymbol?> <?= number_format((float)$invoiceData['total_amount'] + $totalDiscount, 2) ?></td>
                                        </tr>
                                        <tr style="background-color: #f1f5f9;">
                                            <td style="padding: 10px; color: #1a202c; font-weight: bold; font-size: 13px; border-top: 1px solid #cbd5e1; border-bottom-left-radius: 6px;">Total Net Amount</td>
                                            <td align="right" style="padding: 10px; text-align: right; color: #3F77F1; font-weight: bold; font-size: 13px; border-top: 1px solid #cbd5e1; border-bottom-right-radius: 6px;"><?=$currencySymbol?> <?= number_format($grandTotal - (float)($voucherPaymentAmount ?? 0) - (float)($returnBillPaymentAmount ?? 0), 2) ?></td>
                                        </tr>
                                    </table>

                                    <!-- Payment Methods List -->
                                    <table width="100%" cellpadding="0" cellspacing="0" style="font-size: 11px; font-family: Arial, sans-serif;">
                                        <?php 
                                        if (!empty($invoicePayments)):
                                            foreach ($invoicePayments as $paymentRow): 
                                                $pDesc = trim((string)($paymentRow['description'] ?? ''));
                                                $pMethod = trim((string)($paymentRow['method'] ?? ''));
                                                $pAmount = (float)($paymentRow['amount'] ?? 0);
                                                if (strtolower($pDesc) === 'change retuned' || strtolower($pDesc) === 'change returned') continue;
                                        ?>
                                        <tr>
                                            <td style="color: #1a202c; font-weight: bold; padding: 4px 0;"><?= htmlspecialchars($pMethod === 'MultiplePay' ? 'Multi-pay' : $pMethod) ?></td>
                                            <td align="right" style="text-align: right; font-weight: bold; color: #1a202c; padding: 4px 0;"><?=$currencySymbol?> <?= number_format($pAmount, 2) ?></td>
                                        </tr>
                                        <?php 
                                            endforeach;
                                        endif; 
                                        ?>
                                        <?php if ($printedPaidAmount !== null): ?>
                                        <tr style="border-top: 1px dashed #cbd5e1;">
                                            <td style="color: #4a5568; font-weight: bold; padding: 6px 0 4px 0;">Cash Received</td>
                                            <td align="right" style="text-align: right; font-weight: bold; color: #2d3748; padding: 6px 0 4px 0;"><?=$currencySymbol?> <?= number_format($printedPaidAmount, 2) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if ($changeMoney > 0): ?>
                                        <tr>
                                            <td style="color: #ef4444; font-weight: bold; padding: 4px 0;">Change Due / Balance Returned</td>
                                            <td align="right" style="text-align: right; font-weight: bold; color: #ef4444; padding: 4px 0;"><?=$currencySymbol?> <?= number_format($changeMoney, 2) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Environmental Notice Row -->
                <tr>
                    <td style="padding: 0 25px 15px 25px;">
                        <table width="100%" cellpadding="12" cellspacing="0" style="background-color: #e8eefc; border: 1px solid #adc9f8; border-radius: 8px; font-family: Arial, sans-serif; font-size: 11px; color: #1e3a8a; line-height: 1.4;">
                            <tr>
                                <td style="width: 6%; font-size: 24px; text-align: center; padding: 0 10px 0 0; vertical-align: middle;">🛍️</td>
                                <td style="font-weight: bold; padding: 0; vertical-align: middle;">
                                    Let's save the environment together. Bring back your reusable bags when shopping with us.
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- System generated footnotes -->
                <tr>
                    <td style="padding: 15px 25px 30px 25px; text-align: center; color: #718096; font-size: 10px; line-height: 1.8; font-family: Arial, sans-serif; border-top: 1px solid #edf2f7;">
                        <div style="margin-bottom: 4px;">- Please use this bill as a reference if you have any price discrepancies, refunds or product returns. Only applicable for 7 days from today -</div>
                        <div style="margin-bottom: 4px;">- This is a system generated email. Please do not reply. Any queries please email us on <a href="mailto:<?= htmlspecialchars($clientData['email'] ?? 'support@ultrapos.com') ?>" style="color: #3F77F1; text-decoration: none; font-weight: bold;"><?= htmlspecialchars($clientData['email'] ?? 'support@ultrapos.com') ?></a> or call our hotline via <span style="font-weight: bold; color: #2d3748;"><?= htmlspecialchars($companyPhone ?: ($clientData['phone'] ?? '-')) ?></span>. -</div>
                        
                    </td>
                </tr>

                <!-- Bottom Accent Bar -->
                <tr>
                    <td height="16" style="background-color: #3F77F1; font-size: 1px; line-height: 1px;">&nbsp;</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
