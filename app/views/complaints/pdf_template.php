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
            margin-bottom: 150px; /* Space for the footer */
        }

        .footer-image {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            display: block;
            z-index: -1;
        }
    </style>
</head>
<body>

    <!-- Letterhead Image -->
    <?php 
    $letterhead_path = APPROOT . '/../public/img/letterhead.jpg';
    if(file_exists($letterhead_path)){
        $img_data = base64_encode(file_get_contents($letterhead_path));
        $img_src = 'data:image/jpeg;base64,' . $img_data;
    } else {
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

    <!-- Footer Image -->
    <?php 
    $footer_path = APPROOT . '/../public/img/footer.jpg';
    if(file_exists($footer_path)){
        $footer_img_data = base64_encode(file_get_contents($footer_path));
        $footer_img_src = 'data:image/jpeg;base64,' . $footer_img_data;
    } else {
        $footer_img_src = ''; 
    }
    ?>
    <?php if($footer_img_src): ?>
        <img src="<?php echo $footer_img_src; ?>" class="footer-image" alt="Footer">
    <?php endif; ?>

<div class="content-wrapper">
    <table style="width: 100%; font-size: 11px; margin-bottom: 30px; border-collapse: collapse; border: none; line-height: 1.2;">
        <tr>
            <td style="text-align: left; padding-right: 2px;">ඔබේ අංකය</td>
            <td rowspan="3" style="font-size: 28px; font-weight: 300; vertical-align: middle;">}</td>
            <td rowspan="3" style="width: 25%; font-weight: bold; font-size: 13px; vertical-align: middle; padding-left: 5px;"><?php echo $data['complaint']->complaint_no; ?></td>
            
            <td style="text-align: left; padding-right: 2px;">මගේ අංකය</td>
            <td rowspan="3" style="font-size: 28px; font-weight: 300; vertical-align: middle;">}</td>
            <td rowspan="3" style="width: 25%; font-weight: bold; font-size: 13px; vertical-align: middle; padding-left: 5px;">NCP/GOV/2/7</td>
            
            <td style="text-align: left; padding-right: 2px;">දිනය</td>
            <td rowspan="3" style="font-size: 28px; font-weight: 300; vertical-align: middle;">}</td>
            <td rowspan="3" style="font-weight: bold; font-size: 13px; vertical-align: middle; padding-left: 5px;"><?php echo date('Y.m.d', strtotime($data['complaint']->date)); ?> .</td>
        </tr>
        <tr>
            <td style="text-align: left; padding-right: 2px;">உமது இல</td>
            <td style="text-align: left; padding-right: 2px;">எனது இல</td>
            <td style="text-align: left; padding-right: 2px;">திகதி</td>
        </tr>
        <tr>
            <td style="text-align: left; padding-right: 2px;">Your No</td>
            <td style="text-align: left; padding-right: 2px;">My No</td>
            <td style="text-align: left; padding-right: 2px;">Date</td>
        </tr>
    </table>

    <div class="letter-content">
        <div class="department-address">
            <?php 
                if (!empty($data['complaint']->department_name)) {
                    echo htmlspecialchars($data['complaint']->department_name);
                } elseif (!empty($data['dispatched_departments'])) {
                    echo htmlspecialchars($data['dispatched_departments'][0]->name);
                } else {
                    echo '[Department]';
                }
            ?>,<br>
            උතුරු මැද පළාත.
        </div>

        <div class="letter-intro">
            <span style="font-weight: bold; text-decoration: underline;">ගරු ආණ්ඩුකාරතුමා වෙත ඉදිරිපත් වී ඇති ලිපි</span> <br><br>
            ගරු ආණ්ඩුකාරතුමා වෙත ඉදිරිපත් වී ඇති පහත සඳහන් අභියාචනය එතුමාගේ සටහන අනුව අවශ්‍ය ඉදිරි 
            කටයුතු සඳහා මේ සමඟ ඔබ වෙත යොමු කරමි.
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
            02. ඒ අනුව උක්ත ලිපියේ සඳහන් කරුණු සම්බන්ධයෙන් පරීක්ෂා කර බලා යොදා ඇති සටහන අනුව අවශ්‍ය 
            කටයුතු සිදුකරන ලෙසත්, ඒ සම්බන්ධයෙන් ලියුම්කරු දැනුවත් කිරීමට අවශ්‍ය කටයුතු සිදුකරන ලෙසත් 
            කාරුණිකව දන්වා සිටිමි.<br><br>
            
            03. තවද මෙම ඉල්ලීම සම්බන්ධයෙන් ඔබ විසින් ගන්නා ලද ක්‍රියාමාර්ග පිළිබඳ වාර්තාවක් ගරු 
            ආණ්ඩුකාරතුමා වෙත ඉදිරිපත් කිරීම සඳහා මෙම ලිපිය ලැබී දින 14 ක් ඇතුළත මා වෙත යොමු කරන ලෙසත්, 
            ඒ සම්බන්ධයෙන් අදාල අභියාචනාකරු දැනුවත් කරන ලෙසත් කාරුණිකව දන්වා සිටිමි. (ගරු ආණ්ඩුකාරතුමා 
            වෙත ඉදිරිපත් කිරීම සඳහා පිළිතුරු ලිපි සකස්කර එවීමේදී අභියාචනයේ පිටපතක් (ඇමුණුම් රහිතව) අමුණා 
            එවන ලෙසත්, ලිපියේ අංකය සඳහන් කොට එවන ලෙසත්, වැඩිදුරටත් කාරුණිකව දන්වා සිටිමි.)
        </div>

        <?php
        $isApprovedByGS = strpos($data['complaint']->status, 'Approved by GS') !== false;
        
        $signSrc = '';
        $sealSrc = '';
        if ($isApprovedByGS) {
            $signPath = APPROOT . '/../public/img/sign.png';
            $sealPath = APPROOT . '/../public/img/seal.png';
            
            if (file_exists($signPath)) {
                $signSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($signPath));
            }
            if (file_exists($sealPath)) {
                $sealSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($sealPath));
            }
        }
        ?>

        <table style="border-collapse: collapse; border: none; margin-top: 20px;">
            <tr>
                <td style="vertical-align: top; border: none; padding: 0; padding-right: 40px;">
                    <?php if ($isApprovedByGS && !empty($signSrc)): ?>
                        <div style="">
                            <img src="<?php echo $signSrc; ?>" style="width: 120px; height: auto;" />
                        </div>
                    <?php else: ?>
                        <div style="height: 60px;"></div>
                    <?php endif; ?>
                    <div class="signature" style="margin-top: 0; white-space: nowrap;">
                        (නන්දන ගලගොඩ)<br>
                        ආණ්ඩුකාර ලේකම්<br>
                        උතුරු මැද පළාත
                    </div>
                </td>
                <td style="vertical-align: bottom; border: none; padding: 0;">
                    <?php if ($isApprovedByGS && !empty($sealSrc)): ?>
                        <div style="margin-bottom: -10px;">
                            <img src="<?php echo $sealSrc; ?>" style="width: 160px; height: auto;" />
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <div class="cc-list">
            පිටපත් - <br>
            <?php 
            if (!empty($data['dispatched_departments'])) {
                $i = 1;
                foreach($data['dispatched_departments'] as $dept) {
                    echo str_pad($i, 2, '0', STR_PAD_LEFT) . '. ' . htmlspecialchars($dept->name) . ' - කා.දැ.පි.<br>';
                    $i++;
                }
            } else {
                // Fallback or empty if not yet dispatched
                echo '01. අදාල අභියාචනාකරු (' . htmlspecialchars($data['complaint']->applicant_name) . ') - කා.දැ.පි.';
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>
