<!doctype html>
<html lang="de">

<head>
    <meta charset="UTF-8">
</head>

<body style="margin:0;padding:0;background:#f0ece0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0ece0;padding:30px 0;">
        <tr>
            <td align="center">

                <!-- Container -->
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff;color:#1a1a1a;font-family:Georgia,'Times New Roman',serif;">

                    <!-- Header -->
                    <tr>
                        <td style="padding:28px 32px 22px;background:#000000;border-bottom:3px solid #c9a84c;">
                            <div style="font-family:Georgia,serif;font-size:20px;font-weight:400;font-style:italic;color:#f5f0e8;letter-spacing:0.05em;">
                                Sons of Zion
                            </div>
                            <div style="margin-top:6px;font-family:'Courier New',Courier,monospace;font-size:10px;letter-spacing:0.25em;text-transform:uppercase;color:#c9a84c;">
                                Neue Kontaktanfrage
                            </div>
                        </td>
                    </tr>

                    <!-- Accent Line -->
                    <tr>
                        <td style="height:3px;background:linear-gradient(90deg,#c9a84c,#f5f0e8,#c9a84c);"></td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:32px;">
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.65;color:#1a1a1a;">

                                <tr>
                                    <td style="padding-bottom:18px;">
                                        <div style="font-family:'Courier New',Courier,monospace;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#8b7a3a;margin-bottom:4px;">Name</div>
                                        <div style="font-size:16px;font-family:Georgia,serif;"><?= htmlspecialchars($name) ?></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom:18px;border-top:1px solid #e8e0cc;padding-top:18px;">
                                        <div style="font-family:'Courier New',Courier,monospace;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#8b7a3a;margin-bottom:4px;">E-Mail</div>
                                        <div style="font-size:15px;"><?= htmlspecialchars($email) ?></div>
                                    </td>
                                </tr>
                                <?php if (!empty($phone)): ?>
                                <tr>
                                    <td style="padding-bottom:18px;border-top:1px solid #e8e0cc;padding-top:18px;">
                                        <div style="font-family:'Courier New',Courier,monospace;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#8b7a3a;margin-bottom:4px;">Telefon / WhatsApp</div>
                                        <div style="font-size:15px;"><?= htmlspecialchars($phone) ?></div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($message)): ?>
                                <tr>
                                    <td style="border-top:1px solid #e8e0cc;padding-top:18px;">
                                        <div style="font-family:'Courier New',Courier,monospace;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#8b7a3a;margin-bottom:6px;">Nachricht</div>
                                        <div style="padding:16px 18px;background:#faf7f0;
                                                    border-left:3px solid #c9a84c;
                                                    white-space:pre-line;font-size:15px;line-height:1.75;color:#2a2a2a;font-family:Georgia,serif;">
                                            <?= htmlspecialchars($message) ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>

                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:18px 32px 24px;
                                   font-family:'Courier New',Courier,monospace;
                                   font-size:11px;color:#8b7a3a;
                                   background:#faf7f0;
                                   border-top:1px solid #e8e0cc;">
                            Diese Nachricht wurde über das Kontaktformular auf
                            <strong>sons-of-zion.org</strong> gesendet.<br>
                            Bitte direkt auf diese E-Mail antworten, um mit der Person in Kontakt zu treten.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>
</body>

</html>
