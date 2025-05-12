<?php
function uploadImage($request, $userID)
{
    if (isset($request['fileInput']) && $request['fileInput']['error'] === UPLOAD_ERR_OK) {
        $file = $request['fileInput'];
        $currentDate = date('Ymd_His'); // Format: YYYYMMDD_HHMMSS
        $uploadDir = __DIR__ . "/../../public/uploads/reports/{$userID}/";
        $fileName = $currentDate;
        $filePath = $uploadDir . $fileName;

        // Check if directory exists, if not, create it
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $uploadDir));
            }
        }

        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filePath .= '.' . $fileExtension;

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return "{$userID}/{$fileName}.{$fileExtension}";
        } else {
            return null;
        }
    }

    return null;
}
