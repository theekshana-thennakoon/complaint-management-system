<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <title>Official Letter - <?php echo $data['complaint']->complaint_no; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Sinhala:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Noto Sans Sinhala', 'DejaVu Sans', sans-serif !important;
        }

        @page {
            margin: 0px;
        }

        body {
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .header-container {
            position: relative;
            width: 100%;
        }

        .header-image {
            width: 100%;
            margin: 0;
            padding: 0;
            display: block;
        }

        .overlay-detail {
            position: absolute;
            background-color: #ffffff;
            padding: 8px 30px;
            font-size: 14px;
            font-weight: bold;
            z-index: 10;
        }

        .my-no {
            bottom: 25px;
            left: 250px;
        }

        .date-val {
            bottom: 25px;
            right: 150px;
        }

        .content-wrapper {
            padding: 20px 40px;
        }

        .ref-table {
            width: 100%;
            margin-bottom: 40px;
            font-size: 12px;
        }
        .ref-table td {
            vertical-align: top;
        }

        .letter-content {
            /* margin-top: 10px; */
        }

        .department-address {
            margin-bottom: 30px;
        }

        .letter-intro {
            margin-bottom: 20px;
            text-align: justify;
            line-height: 1.5;
        }

        table.details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table.details-table th, table.details-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .letter-body {
            margin-bottom: 40px;
            text-align: justify;
            line-height: 1.5;
        }

        .signature {
            margin-top: 20px;
            line-height: 1.5;
        }

        .cc-list {
            margin-top: 50px;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <!-- Letterhead Image -->
    <?php 
    $letterhead_path = APPROOT . '/../public/img/letterhead.jpg';
    if(file_exists($letterhead_path)){
        // In DOMPDF, absolute local paths work best if isRemoteEnabled is true or using chroot
        $img_src = $letterhead_path;
    } else {
        // Fallback or placeholder
        $img_src = ''; 
    }
    ?>
    <?php if($img_src): ?>
        <img src="<?php echo $img_src; ?>" class="header-image" alt="Letterhead">
    <?php else: ?>
        <div style="text-align:center; padding: 50px; border: 2px dashed #ccc;">
            <h3>[Please place 'letterhead.jpg' in the public/img folder]</h3>
        </div>
    <?php endif; ?>

<div class="content-wrapper">
    <div class="letter-content">
        <div class="department-address">
            <?php echo $data['complaint']->department_name; ?>,<br>
            උතුරු මැද පළාත.
        </div>

        <div class="letter-intro">
            <span style="font-weight: bold; text-decoration: underline;">ගරු ආණ්ඩුකාරතුමාගේ ගෙත ඉදිරිපත් වී ඇති ලිපි</span> <br><br>
            ගරු ආණ්ඩුකාරතුමා ලෙත් ඉදිරිපත් වී ඇති පහත් සඳහන් අභියාචනය එතුමාලේ සටහන අනුෙ අෙශය ඉදිරි 
            කටයුතු සඳහා ලම් සමඟ ඔබ ලෙත් ලයාමු කරමි.
        </div>

        <table class="details-table">
            <thead>
                <tr>
                    <th width="10%">අනු අංක</th>
                    <th width="40%">ලිපියේ අංකය</th>
                    <th width="50%">නම හා කාරණය</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($data['details'])): ?>
                    <?php $i = 1; foreach($data['details'] as $detail): ?>
                        <tr>
                            <td><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo $detail->letter_no; ?></td>
                            <td><?php echo htmlspecialchars($detail->name) . (!empty($detail->subject) ? '<br><small>' . htmlspecialchars($detail->subject) . '</small>' : ''); ?></td>
                        </tr>
                    <?php $i++; endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">විස්තර නොමැත (No details)</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="letter-body">
            02. ඒ අනුෙ උක්ත් ලිපිලේ සඳහන් කරුණු සම්බන්ධලයන් පරීක්ෂා කර බලා ලයාදා ඇති සටහන අනුෙ අෙශය 
            කටයුතු සිදුකරන ලලසත්, ඒ සම්බන්ධලයන් ලියුම්කරු දැනුෙත් කිරීමට අෙශය කටයුතු සිදුකරන ලලසත් 
            කාරුණිකෙ දන්ො සිටිමි.<br><br>
            
            03. ත්ෙද ලමම ඉේීම සම්බන්ධලයන් ඔබ විසින් ගන්නා ලද ක්රියාමාර්ග පිළිබඳ ොර්ත්ාෙක් ගරු 
            ආණ්ඩුකාරතුමා ලෙත් ඉදිරිපත් කිරීම සඳහා ලමම ලිපිය ලැබි  දින 14 ක් ඇතුළත් මා ලෙත් ලයාමු කරන ලලසත්, 
            ඒ සම්බන්ධලයන් අදාල අභියාචනාකරු දැනුෙත් කරන ලලසත් කාරුණිකෙ දන්ො සිටිමි. (ගරු ආණ්ඩුකාරතුමා 
            ලෙත් ඉදිරිපත් කිරීම සඳහා පිළිතුරු ලිපි සකස්කර එවීලම්දි අභියාචනලේ පිටපත්ක් (ඇමුණුම් රහිත්ෙ) අමුණා 
            එෙන ලලසත්, ලිපිලේ අංකය සඳහන් ලකාට එෙන ලලසත්, ෙැඩිදුරටත් කාරුණිකෙ දන්ො සිටිමි.)
        </div>

        <div class="signature">
            (නන්දන ගලග ාඩ)<br>
            ආණ්ඩුකාරෙර ලේකම්<br>
            උතුරු මැද පළාත්
        </div>

        <div class="cc-list">
            පිටපත් - <br>
            01. අදාල අභියාචනාකරු (<?php echo $data['complaint']->applicant_name; ?>) - කා.දැ.පි.
        </div>
    </div>
</div>

</body>
</html>
