<?php
// Enable error reporting for debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php'; // Autoloader for dependencies like Dompdf and FPDI

use Dompdf\Dompdf;
use setasign\Fpdi\Fpdi;

// Get URL query parameters
$document_date = $_GET['document_date'] ?? '';
$document_id = $_GET['document_id'] ?? '';
$text = $_GET['text'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form_date = date('Y-m-d H:i:s');
    $rich_text = $_POST['rich_text'];
    $attachments = $_FILES['attachments'];

    // Generate the form as a PDF (Page 1)
    $dompdf = new Dompdf();
    $dompdf->loadHtml("
        <h1>Form Submission</h1>
        <p><strong>Document Date:</strong> {$document_date}</p>
        <p><strong>Document ID:</strong> {$document_id}</p>
        <p><strong>Text:</strong> {$text}</p>
        <p><strong>Submission Date:</strong> {$form_date}</p>
        <h2>Rich Text</h2>
        <div>{$rich_text}</div>
    ");
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $form_pdf = tempnam(sys_get_temp_dir(), 'form') . '.pdf';
    file_put_contents($form_pdf, $dompdf->output());

    // Create a combined PDF
    $pdf = new Fpdi();
    $pdf->setSourceFile($form_pdf);
    $pdf->AddPage();
    $pdf->useTemplate($pdf->importPage(1));

    // Process attachments and append them to the PDF
    foreach ($attachments['tmp_name'] as $key => $tmp_name) {
        if (is_uploaded_file($tmp_name)) {
            $file_name = $attachments['name'][$key]; // Get the original filename
            $file_type = mime_content_type($tmp_name);
            if ($file_type == 'application/pdf') {
                $page_count = $pdf->setSourceFile($tmp_name); // Get number of pages
                for ($page = 1; $page <= $page_count; $page++) {
                    $pdf->AddPage();
                    $pdf->useTemplate($pdf->importPage($page));
                    // Add filename to the bottom right corner of the first page
                    if ($page === 1) {
                        $pdf->SetFont('Arial', '', 10);
                        $pdf->SetTextColor(100, 100, 100);
                        $pdf->SetXY(150, 270); // Adjust position as needed
                        $pdf->Cell(0, 10, $file_name, 0, 0, 'R');
                    }
                }
            } elseif (in_array($file_type, ['image/jpeg', 'image/png'])) {
                $pdf->AddPage();
                $pdf->Image($tmp_name, 10, 10, 190); // Adjust dimensions if needed
                // Add filename to the bottom right corner of the first page
                $pdf->SetFont('Arial', '', 10);
                $pdf->SetTextColor(100, 100, 100);
                $pdf->SetXY(150, 270); // Adjust position as needed
                $pdf->Cell(0, 10, $file_name, 0, 0, 'R');
            }
        }
    }

    $final_pdf = tempnam(sys_get_temp_dir(), 'final') . '.pdf';
    $pdf->Output($final_pdf, 'F');

    // Send the email
    $to = 'olsenit@gmail.com';
    $subject = 'Form Submission with Attachments';
    $message = 'Please find the attached merged PDF.';
    $headers = [
        'From: no-reply@example.com',
        'Content-Type: multipart/mixed; boundary="boundary123"'
    ];

    // Email body with attachment
    $email_body = "--boundary123\r\n";
    $email_body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
    $email_body .= $message . "\r\n\r\n";
    $email_body .= "--boundary123\r\n";
    $email_body .= "Content-Type: application/pdf; name=\"submission.pdf\"\r\n";
    $email_body .= "Content-Disposition: attachment; filename=\"submission.pdf\"\r\n";
    $email_body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $email_body .= chunk_split(base64_encode(file_get_contents($final_pdf))) . "\r\n";
    $email_body .= "--boundary123--";

    if (mail($to, $subject, $email_body, implode("\r\n", $headers))) {
        echo 'Form submitted successfully!';
    } else {
        echo 'Error sending email.';
    }

    // Cleanup
    unlink($form_pdf);
    unlink($final_pdf);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Submission Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #editor {
            border: 1px solid #ccc;
            min-height: 200px;
            padding: 10px;
            margin-top: 10px;
            overflow-y: auto;
            background-color: #fff;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1>Document Submission Form</h1>
    <form action="ucmail.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Document Date</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($_GET['document_date'] ?? '') ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Document ID</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($_GET['document_id'] ?? '') ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Text</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($_GET['text'] ?? '') ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Rich Text</label>
            <div id="toolbar" class="mb-2">
                <button type="button" onclick="formatText('bold')" class="btn btn-sm btn-secondary">Bold</button>
                <button type="button" onclick="formatText('italic')" class="btn btn-sm btn-secondary">Italic</button>
                <button type="button" onclick="formatText('underline')" class="btn btn-sm btn-secondary">Underline</button>
                <button type="button" onclick="formatText('insertUnorderedList')" class="btn btn-sm btn-secondary">Bullets</button>
            </div>
            <div id="editor" contenteditable="true"></div>
            <input type="hidden" name="rich_text" id="richTextInput">
        </div>
        <div class="mb-3">
            <label class="form-label">Attachments</label>
            <input type="file" name="attachments[]" class="form-control" multiple>
        </div>
        <button type="submit" class="btn btn-primary" onclick="saveEditorContent()">Submit</button>
    </form>
</div>

<script>
    // Function to format text
    function formatText(command) {
        document.execCommand(command, false, null);
    }

    // Save the editor content to a hidden input field
    function saveEditorContent() {
        const editorContent = document.getElementById('editor').innerHTML;
        document.getElementById('richTextInput').value = editorContent;
    }

    // Allow pasting images into the editor
    document.getElementById('editor').addEventListener('paste', function (e) {
        const items = e.clipboardData.items;
        for (let item of items) {
            if (item.type.startsWith('image/')) {
                const blob = item.getAsFile();
                const reader = new FileReader();
                reader.onload = function (event) {
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.style.maxWidth = '100%';
                    img.style.height = 'auto';
                    document.getElementById('editor').appendChild(img);
                };
                reader.readAsDataURL(blob);
            }
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

