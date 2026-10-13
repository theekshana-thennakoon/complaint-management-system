<?php
/**
 * Email Template — Complaint Forwarding Notice
 * Rendered server-side and sent as HTML email body.
 * All CSS must be INLINE for email client compatibility.
 *
 * Expected variables injected before include:
 *   $emailData = [
 *     'org_name'         => string,
 *     'complaint_no'     => string,
 *     'applicant_name'   => string,
 *     'nic'              => string,
 *     'mobile'           => string,
 *     'address'          => string,
 *     'subject'          => string,
 *     'category_name'    => string,
 *     'district'         => string,
 *     'date'             => string,
 *     'description'      => string,
 *     'signatory_name'   => string,
 *     'signatory_title'  => string,
 *     'sent_date'        => string,  // formatted date of sending
 *   ]
 */

$org_name        = htmlspecialchars($emailData['org_name']        ?? '');
$complaint_no    = htmlspecialchars($emailData['complaint_no']    ?? '');
$applicant_name  = htmlspecialchars($emailData['applicant_name']  ?? '');
$nic             = htmlspecialchars($emailData['nic']             ?? '');
$mobile          = htmlspecialchars($emailData['mobile']          ?? '');
$address         = htmlspecialchars($emailData['address']         ?? '');
$subject_text    = htmlspecialchars($emailData['subject']         ?? '');
$category_name   = htmlspecialchars($emailData['category_name']   ?? '');
$district        = htmlspecialchars($emailData['district']        ?? '');
$date_received   = htmlspecialchars($emailData['date']            ?? '');
$description_raw = $emailData['description'] ?? '';
$description     = htmlspecialchars(mb_strlen($description_raw) > 500 ? mb_substr($description_raw, 0, 500) . '...' : $description_raw);
$sig_name        = htmlspecialchars($emailData['signatory_name']  ?? 'නන්දන ගලබොඩ');
$sig_title_raw   = $emailData['signatory_title'] ?? "ආණ්ඩුකාරවර ලේකම්,\nආණ්ඩුකාරවර ලේකම් කාර්යාලය,\nඋතුරු මැද පළාත.";
$sig_title       = nl2br(htmlspecialchars($sig_title_raw));
$sent_date       = htmlspecialchars($emailData['sent_date']       ?? date('Y-m-d'));
?><!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Complaint Reference — <?php echo $complaint_no; ?></title>
</head>
<body style="margin:0;padding:0;background-color:#f0f4f8;font-family:'Segoe UI',Arial,sans-serif;">

  <!-- Outer wrapper -->
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f0f4f8;padding:30px 0;">
    <tr>
      <td align="center">

        <!-- Email card -->
        <table width="640" cellpadding="0" cellspacing="0" border="0"
               style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.10);max-width:640px;width:100%;">

          <!-- ── HEADER BANNER ── -->
          <tr>
            <td style="background:linear-gradient(135deg,#1a6e3c 0%,#2d9150 50%,#1a5c73 100%);padding:36px 40px 28px 40px;text-align:center;">
              <p style="margin:0 0 6px 0;font-size:11px;color:rgba(255,255,255,0.75);letter-spacing:2px;text-transform:uppercase;">
                Official Communication
              </p>
              <h1 style="margin:0;font-size:22px;font-weight:700;color:#ffffff;letter-spacing:0.5px;">
                Governor's Secretariat
              </h1>
              <p style="margin:4px 0 0 0;font-size:14px;color:rgba(255,255,255,0.85);">
                ආණ්ඩුකාරවර ලේකම් කාර්යාලය
              </p>
              <!-- Decorative line -->
              <div style="margin:18px auto 0;width:60px;height:3px;background:rgba(255,255,255,0.4);border-radius:2px;"></div>
            </td>
          </tr>

          <!-- ── DATE & REF BADGE ── -->
          <tr>
            <td style="background:#f8fafb;padding:16px 40px;border-bottom:1px solid #e8edf2;">
              <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td style="font-size:13px;color:#64748b;">
                    Date: <strong style="color:#334155;"><?php echo $sent_date; ?></strong>
                  </td>
                  <td align="right">
                    <span style="background:#1a6e3c;color:#ffffff;font-size:11px;font-weight:700;padding:4px 14px;border-radius:20px;letter-spacing:0.5px;">
                      Ref: <?php echo $complaint_no; ?>
                    </span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- ── GREETING & BODY ── -->
          <tr>
            <td style="padding:32px 40px 24px 40px;">
              <p style="margin:0 0 20px 0;font-size:15px;color:#334155;line-height:1.7;">
                Dear <strong style="color:#1a6e3c;"><?php echo $org_name; ?></strong>,
              </p>
              <p style="margin:0 0 16px 0;font-size:15px;color:#475569;line-height:1.8;">
                I write to you on behalf of the Governor's Secretariat to bring to your attention the following complaint
                received by this office. Kindly review the details below and take the appropriate action within your
                purview at the earliest convenience.
              </p>
              <p style="margin:0;font-size:15px;color:#475569;line-height:1.8;">
                The complaint has been formally logged under reference number
                <strong style="color:#1a6e3c;"><?php echo $complaint_no; ?></strong>
                and is forwarded to your organisation for necessary action and/or response.
              </p>
            </td>
          </tr>

          <!-- ── SECTION DIVIDER ── -->
          <tr>
            <td style="padding:0 40px;">
              <div style="height:1px;background:linear-gradient(90deg,#1a6e3c33,#2d915033,transparent);"></div>
            </td>
          </tr>

          <!-- ── COMPLAINT DETAILS CARD ── -->
          <tr>
            <td style="padding:24px 40px 0 40px;">
              <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;overflow:hidden;">

                <!-- Card header -->
                <div style="background:linear-gradient(90deg,#1a6e3c,#2d9150);padding:12px 20px;">
                  <p style="margin:0;font-size:13px;font-weight:700;color:#ffffff;letter-spacing:0.8px;text-transform:uppercase;">
                    📋 Complaint Details
                  </p>
                </div>

                <!-- Details table -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">

                  <!-- Applicant Name -->
                  <tr style="border-bottom:1px solid #dcfce7;">
                    <td style="padding:11px 20px;width:38%;font-size:12px;font-weight:600;color:#166534;background:#f0fdf4;text-transform:uppercase;letter-spacing:0.5px;">
                      Applicant Name
                    </td>
                    <td style="padding:11px 20px;font-size:14px;color:#1e293b;background:#ffffff;">
                      <?php echo $applicant_name; ?>
                    </td>
                  </tr>

                  <!-- NIC -->
                  <tr style="border-bottom:1px solid #dcfce7;">
                    <td style="padding:11px 20px;font-size:12px;font-weight:600;color:#166534;background:#f0fdf4;text-transform:uppercase;letter-spacing:0.5px;">
                      NIC Number
                    </td>
                    <td style="padding:11px 20px;font-size:14px;color:#1e293b;background:#ffffff;">
                      <?php echo $nic; ?>
                    </td>
                  </tr>

                  <!-- Contact -->
                  <tr style="border-bottom:1px solid #dcfce7;">
                    <td style="padding:11px 20px;font-size:12px;font-weight:600;color:#166534;background:#f0fdf4;text-transform:uppercase;letter-spacing:0.5px;">
                      Contact
                    </td>
                    <td style="padding:11px 20px;font-size:14px;color:#1e293b;background:#ffffff;">
                      <?php echo $mobile; ?>
                    </td>
                  </tr>

                  <!-- Address -->
                  <tr style="border-bottom:1px solid #dcfce7;">
                    <td style="padding:11px 20px;font-size:12px;font-weight:600;color:#166534;background:#f0fdf4;text-transform:uppercase;letter-spacing:0.5px;">
                      Address
                    </td>
                    <td style="padding:11px 20px;font-size:14px;color:#1e293b;background:#ffffff;">
                      <?php echo nl2br($address); ?>
                    </td>
                  </tr>

                  <!-- Subject -->
                  <tr style="border-bottom:1px solid #dcfce7;">
                    <td style="padding:11px 20px;font-size:12px;font-weight:600;color:#166534;background:#f0fdf4;text-transform:uppercase;letter-spacing:0.5px;">
                      Subject
                    </td>
                    <td style="padding:11px 20px;font-size:14px;color:#1e293b;font-weight:600;background:#ffffff;">
                      <?php echo $subject_text; ?>
                    </td>
                  </tr>

                  <!-- Category -->
                  <tr style="border-bottom:1px solid #dcfce7;">
                    <td style="padding:11px 20px;font-size:12px;font-weight:600;color:#166534;background:#f0fdf4;text-transform:uppercase;letter-spacing:0.5px;">
                      Category
                    </td>
                    <td style="padding:11px 20px;font-size:14px;color:#1e293b;background:#ffffff;">
                      <?php echo $category_name; ?>
                    </td>
                  </tr>

                  <!-- District -->
                  <tr style="border-bottom:1px solid #dcfce7;">
                    <td style="padding:11px 20px;font-size:12px;font-weight:600;color:#166534;background:#f0fdf4;text-transform:uppercase;letter-spacing:0.5px;">
                      District
                    </td>
                    <td style="padding:11px 20px;font-size:14px;color:#1e293b;background:#ffffff;">
                      <?php echo $district; ?>
                    </td>
                  </tr>

                  <!-- Date -->
                  <tr style="border-bottom:1px solid #dcfce7;">
                    <td style="padding:11px 20px;font-size:12px;font-weight:600;color:#166534;background:#f0fdf4;text-transform:uppercase;letter-spacing:0.5px;">
                      Date Received
                    </td>
                    <td style="padding:11px 20px;font-size:14px;color:#1e293b;background:#ffffff;">
                      <?php echo $date_received; ?>
                    </td>
                  </tr>

                  <!-- Description -->
                  <?php if (!empty($description)): ?>
                  <tr>
                    <td style="padding:11px 20px;font-size:12px;font-weight:600;color:#166534;background:#f0fdf4;text-transform:uppercase;letter-spacing:0.5px;vertical-align:top;">
                      Description
                    </td>
                    <td style="padding:11px 20px;font-size:14px;color:#475569;line-height:1.7;background:#ffffff;">
                      <?php echo nl2br($description); ?>
                    </td>
                  </tr>
                  <?php endif; ?>

                </table>
              </div>
            </td>
          </tr>

          <!-- ── CLOSING NOTE ── -->
          <tr>
            <td style="padding:28px 40px 10px 40px;">
              <p style="margin:0;font-size:14px;color:#475569;line-height:1.8;">
                We kindly request that you acknowledge receipt of this communication and provide a response or update
                on the action taken at your earliest opportunity. Please do not hesitate to contact this office should
                you require any further information.
              </p>
            </td>
          </tr>

          <!-- ── SIGNATORY ── -->
          <tr>
            <td style="padding:20px 40px 32px 40px;">
              <p style="margin:0 0 4px 0;font-size:14px;color:#475569;">Yours faithfully,</p>
              <div style="margin-top:12px;padding:16px 20px;background:#f8fafb;border-left:4px solid #1a6e3c;border-radius:0 8px 8px 0;display:inline-block;">
                <p style="margin:0;font-size:15px;font-weight:700;color:#1a6e3c;"><?php echo $sig_name; ?></p>
                <p style="margin:4px 0 0;font-size:13px;color:#64748b;line-height:1.6;"><?php echo $sig_title; ?></p>
              </div>
            </td>
          </tr>

          <!-- ── FOOTER ── -->
          <tr>
            <td style="background:#1e293b;padding:20px 40px;text-align:center;">
              <p style="margin:0 0 6px 0;font-size:12px;color:#94a3b8;">
                Governor's Secretariat, North Central Province, Sri Lanka
              </p>
              <p style="margin:0;font-size:11px;color:#64748b;">
                ⚠️ This is an official automated email. Please do not reply directly to this message.
              </p>
            </td>
          </tr>

        </table>
        <!-- End email card -->

      </td>
    </tr>
  </table>

</body>
</html>
