<?php
function uploadImage($request, $filename, $path)
{
    if (isset($request['fileInput']) && $request['fileInput']['error'] === UPLOAD_ERR_OK) {
        $file = $request['fileInput'];
        $uploadDir = __DIR__ . "/../../{$path}/";
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filePath = $uploadDir . $filename . '.png';

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return "{$filename}.png";
        } else {
            return null;
        }
    }

    return null;
}
