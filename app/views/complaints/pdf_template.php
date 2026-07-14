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
            size: A4 portrait;
        }

        @media print {
            html, body {
                width: 210mm;
                min-height: 297mm;
                margin: 0 !important;
                padding: 0 !important;
                background-color: white;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .footer-image {
                position: fixed;
                bottom: 0;
            }
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
            z-index: 0;
        }
    </style>
</head>
<body oncontextmenu="return false;">

    <table style="width: 100%; border: none; border-collapse: collapse;">
        <thead>
            <tr>
                <td style="padding: 0; border: none;">
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
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding: 0; border: none;">
                    <div class="content-wrapper">
    <?php 
    $footer_path = APPROOT . '/../public/img/footer.jpg';
    if(file_exists($footer_path)){
        $footer_img_data = base64_encode(file_get_contents($footer_path));
        $footer_img_src = 'data:image/jpeg;base64,' . $footer_img_data;
    } else {
        $footer_img_src = ''; 
    }
    
    $my_number = '';
    if ($data['complaint']->district == 'අනුරාධපුරය') {
        $my_number = 'NCP/GOV/2/7';
    } elseif ($data['complaint']->district == 'පොළොන්නරුව') {
        $my_number = 'NCP/GOV/01/PNPD';
    }
    ?>
    <table style="width: 100%; font-size: 11px; margin-bottom: 30px; border-collapse: collapse; border: none; line-height: 1.2;">
        <tr>
            <td style="text-align: left; padding-right: 2px;">ඔබේ අංකය</td>
            <td rowspan="3" style="font-size: 28px; font-weight: 300; vertical-align: middle;">}</td>
            <td rowspan="3" style="width: 25%; font-weight: bold; font-size: 13px; vertical-align: middle; padding-left: 5px;">
                <!-- <?php echo $data['complaint']->complaint_no; ?> -->
            </td>
            
            <td style="text-align: left; padding-right: 2px;">මගේ අංකය</td>
            <td rowspan="3" style="font-size: 28px; font-weight: 300; vertical-align: middle;">}</td>
            <td rowspan="3" style="width: 25%; font-weight: bold; font-size: 13px; vertical-align: middle; padding-left: 5px;"><?php echo htmlspecialchars($my_number); ?></td>
            
            <td style="text-align: left; padding-right: 2px;">දිනය</td>
            <td rowspan="3" style="font-size: 28px; font-weight: 300; vertical-align: middle;">}</td>
            <td rowspan="3" style="font-weight: bold; font-size: 13px; vertical-align: middle; padding-left: 5px;">
                <?php
                // Get current date from the complaint record if available, otherwise use current system date
                $displayDate = !empty($data['complaint']->date)
                    ? date('Y.m.d', strtotime($data['complaint']->date))
                    : date('Y.m.d');
                echo $displayDate;
                ?> .
            </td>
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
            <?php if (!empty($data['complaint']->person)) : ?>
                <?php echo htmlspecialchars($data['complaint']->person); ?>,<br>
            <?php endif; ?>
            <?php 
                if (!empty($data['complaint']->department_name)) {
                    echo htmlspecialchars($data['complaint']->department_name);
                } elseif (!empty($data['dispatched_departments'])) {
                    echo htmlspecialchars($data['dispatched_departments'][0]->name);
                } else {
                    echo '[Department]';
                }
            ?>,<br>
            <?php
                if (!empty($data['complaint']->province)) {
                    echo htmlspecialchars($data['complaint']->province);
                }
            ?>
        </div>

        <div class="letter-intro">
            <?php if($data['complaint']->district != 'පොළොන්නරුව'): ?>
            <span style="font-weight: bold; text-decoration: underline;">ගරු ආණ්ඩුකාරතුමා වෙත ඉදිරිපත් වී ඇති ලිපි</span> <br><br>
            <?php else: ?>
                <span style="font-weight: bold; text-decoration: underline;">පොළොන්නරුව මහජන දිනය :<?= $displayDate; ?></span> <br><br>
            <?php endif; ?>
            <?php
            $standardDefault = 'ගරු ආණ්ඩුකාරතුමා වෙත ඉදිරිපත් වී ඇති පහත සඳහන් අභියාචනය එතුමාගේ සටහන අනුව අවශ්‍ය ඉදිරි කටයුතු සඳහා මේ සමඟ ඔබ වෙත යොමු කරමි.';
            $defaultIntro = $standardDefault;
                  
            if ($data['complaint']->district == 'පොළොන්නරුව') {
                $complaintDate = date('Y.m.d', strtotime($data['complaint']->date));
                $defaultIntro = $complaintDate . ' දින ගරු ආණ්ඩුකාරතුමාගේ ප්‍රධානත්වයෙන් පැවති පොළොන්නරුව මහජන දිනයේ දී ගරු ආණ්ඩුකාරතුමා වෙත ඉදිරිපත් කරන ලද පහත අභියාචනා එතුමාගේ සටහන අනුව අවශ්‍ය ඉදිරි කටයුතු සඳහා මේ සමඟ ඔබ වෙත එවමි.';
            }

            $savedIntro = trim($data['complaint']->letter_intro);
            // If empty or matches the standard JS default, we override it with our computed $defaultIntro
            if (empty($savedIntro) || $savedIntro == $standardDefault) {
                $letterIntro = $defaultIntro;
            } else {
                $letterIntro = nl2br(htmlspecialchars($savedIntro));
            }
            echo $letterIntro;
            ?>
        </div>

        <?php if($data['complaint']->district == 'පොළොන්නරුව'): ?>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
            <thead>
                <tr>
                    <th style="width: 25%; text-align: left; padding: 8px 0; border: none;"><u>මහජන දින අංකය</u></th>
                    <th style="width: 75%; text-align: left; padding: 8px 0; border: none;"><u>ලිපිය ඉදිරිපත් කරන අයගේ නම හා ලිපියේ දක්වා ඇති කාරණය</u></th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($data['details'])): ?>
                    <?php foreach($data['details'] as $detail): ?>
                        <tr>
                            <td style="padding: 12px 10px 12px 0; font-weight: bold; border: none; vertical-align: bottom;">
                                <div style="display: inline-block; min-width: 80%; padding-bottom: 3px;">
                                    <?php echo htmlspecialchars($detail->letter_no); ?>
                                </div>
                            </td>
                            <td style="padding: 12px 0; border: none; vertical-align: bottom;">
                                <div style="border-bottom: 2px dotted #000; width: 100%; padding-bottom: 3px; margin-bottom: <?php echo !empty($detail->subject) ? '10px' : '0'; ?>;">
                                    <?php echo htmlspecialchars($detail->name); ?>
                                </div>
                                <?php if(!empty($detail->subject)): ?>
                                <div style="border-bottom: 2px dotted #000; width: 100%; padding-bottom: 3px;">
                                    <small><?php echo htmlspecialchars($detail->subject); ?></small>
                                </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" style="padding: 12px 0; border: none; text-align: center;">විස්තර නොමැත (No details)</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php else: ?>
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
                            <td style="text-align: center;"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo htmlspecialchars($detail->letter_no); ?></td>
                            <td><?php echo htmlspecialchars($detail->name) . (!empty($detail->subject) ? '<br><small>' . htmlspecialchars($detail->subject) . '</small>' : ''); ?></td>
                        </tr>
                    <?php $i++; endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center;">විස්තර නොමැත (No details)</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php endif; ?>

        <div class="letter-body">
             <?php
            $frontendDefaultBody = '02. ඒ අනුව උක්ත ලිපියේ සඳහන් කරුණු සම්බන්ධයෙන් පරීක්ෂා කර බලා යොදා ඇති සටහන අනුව අවශ්‍ය කටයුතු සිදුකරන ලෙසත්, ඒ සම්බන්ධයෙන් ලිපිය ලැබූ දැනුවත් කිරීමට අවශ්‍ය කටයුතු සිදුකරන ලෙසත් කාරුණිකව දන්වා සිටිමි.' . "\n\n" . '03. තවද මෙම ඉල්ලීම සම්බන්ධයෙන් ඔබ විසින් ගන්නා ලද ක්‍රියාමාර්ග පිළිබඳ වාර්තාවක් ගරු ආණ්ඩුකාරතුමා වෙත ඉදිරිපත් කිරීම සඳහා මෙම ලිපිය ලැබී දින 14 ක් ඇතුළත මා වෙත යොමු කරන ලෙසත්, ඒ සම්බන්ධයෙන් අදාල අභියාචනාකරු දැනුවත් කරන ලෙසත් කාරුණිකව දන්වා සිටිමි. (ගරු ආණ්ඩුකාරතුමා වෙත ඉදිරිපත් කිරීම සඳහා පිළිතුරු ලිපි සකස්කර එවීමේදී අභියාචනයේ පිටපතක් (ඇමුණුම් රහිතව) අමුණා එවන ලෙසත්, ලිපියේ අංකය සඳහන් කොට එවන ලෙසත්, වැඩිදුරටත් කාරුණිකව දන්වා සිටිමි.)';

            $oldBackendDefault = '02. ඒ අනුව උක්ත ලිපියේ සඳහන් කරුණු සම්බන්ධයෙන් පරීක්ෂා කර බලා යොදා ඇති සටහන අනුව අවශ්‍ය කටයුතු සිදුකරන ලෙසත්, ඒ සම්බන්ධයෙන් ලියුම්කරු දැනුවත් කිරීමට අවශ්‍ය කටයුතු සිදුකරන ලෙසත් කාරුණිකව දන්වා සිටිමි. 03. තවද මෙම ඉල්ලීම සම්බන්ධයෙන් ඔබ විසින් ගන්නා ලද ක්‍රියාමාර්ග පිළිබඳ වාර්තාවක් ගරු ආණ්ඩුකාරතුමා වෙත ඉදිරිපත් කිරීම සඳහා මෙම ලිපිය ලැබී දින 14 ක් ඇතුළත මා වෙත යොමු කරන ලෙසත්, ඒ සම්බන්ධයෙන් අදාල අභියාචනාකරු දැනුවත් කරන ලෙසත් කාරුණිකව දන්වා සිටිමි. (ගරු ආණ්ඩුකාරතුමා වෙත ඉදිරිපත් කිරීම සඳහා පිළිතුරු ලිපි සකස්කර එවීමේදී අභියාචනයේ පිටපතක් (ඇමුණුම් රහිතව) අමුණා එවන ලෙසත්, ලිපියේ අංකය සඳහන් කොට එවන ලෙසත්, වැඩිදුරටත් කාරුණිකව දන්වා සිටිමි.)';

            $defaultBody = $frontendDefaultBody;

            if ($data['complaint']->district == 'පොළොන්නරුව') {
                $defaultBody = '02. අදාළ අභියාචනා මගින් දක්වා ඇති ඉල්ලීම් සම්බන්ධයෙන් ගත හැකි ක්‍රියා මාර්ගයන් පිළිබඳව අභියාචනා කරුවන් දැනුවත් කරන ලෙසත්, ඊට අදාළව ඔබ විසින් ගන්නා ලබන ක්‍රියා මාර්ගයන් පිළිබඳ තොරතුරු ගරු ආණ්ඩුකාරතුමා වෙත වාර්තා කිරීම සඳහා දින 14 ක් තුල මා වෙත දන්වා එවීමට අවශ්‍ය කටයුතු කරන ලෙසත් එතුමාගේ උපදෙස් පරිදි කාරුණිකව දන්වා සිටිමි.';
            }

            $savedBody = trim($data['complaint']->letter_body);
            
            // Normalize spaces for robust comparison
            $normSaved = preg_replace('/\s+/', ' ', $savedBody);
            $normDefault1 = preg_replace('/\s+/', ' ', $frontendDefaultBody);
            $normDefault2 = preg_replace('/\s+/', ' ', $oldBackendDefault);

            if (empty($savedBody) || $normSaved == $normDefault1 || $normSaved == $normDefault2) {
                $letterBody = nl2br(htmlspecialchars($defaultBody));
            } else {
                $letterBody = nl2br(htmlspecialchars($savedBody));
            }

            echo $letterBody;
            ?>
            
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
                        <?php
                        $sigName  = !empty($data['complaint']->signatory_name)  ? $data['complaint']->signatory_name  : 'නන්දන ගලබොඩ';
                        $sigTitle = "ආණ්ඩුකාර ලේකම්," . "\n" . "උතුරු මැද පළාත.";
                        echo '(' . htmlspecialchars($sigName) . ')<br>';
                        echo nl2br(htmlspecialchars($sigTitle));
                        ?>
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
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td style="padding: 0; border: none;">
                    <div style="height: 100px;"></div> <!-- Space for the fixed footer -->
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Fixed Footer Image -->
    <?php if($footer_img_src): ?>
        <img src="<?php echo $footer_img_src; ?>" class="footer-image" alt="Footer">
    <?php endif; ?>

</body>
</html>
