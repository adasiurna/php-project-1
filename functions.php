<?php declare (strict_types = 1);

require_once 'Exception/FileException.php';
require_once 'config.php';

function isLoggedIn(): bool
{
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] == true;
}

function addFlashMessage(string $messageType, string $text)
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['flash_messages'])) {
        $_SESSION['flash_messages'] = [];
    }

    $_SESSION['flash_messages'][] = [
        'type' => $messageType,
        'text' => $text,
    ];
}

function checkLoginAndRedirect(): void
{
    if (!isLoggedIn()) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Location:' . BASE_URL . '/login.php');
        addFlashMessage('danger', 'You must be logged in!');
        exit();
    }
}

function checkPassword(string $password): bool
{
    return password_verify($password, ADMIN_PASSWORD_HASH);
}

function login(): void
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['admin_logged_in'] = true;
}

function logout(): void
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    session_destroy();
}

function listFolder(string $directory, string $filename, int $level): void
{
    if ($filename != '..' && $filename != '.') {
        for ($i = 0; $i <= $level; $i++) {
            echo ' - ';            
        }
        echo '<i class="fa fa-folder"></i>'.' '.$filename;
        $folderPath = $directory.'/'.$filename;
        printFormCreateFolder($folderPath);
        printFormUploadFile($folderPath);
        printFormDeleteFolder($folderPath);
        listFiles($directory . '/' . $filename, $level + 1);
    }
}

function listFile(string $directory, string $filename, int $level): void
{
    $pathInfo = pathinfo($filename);
    $extension = $pathInfo['extension'];
    for ($i = 0; $i <= $level; $i++) {
        echo ' - ';
    }
    $filePath = $directory . '/' . $filename;
    if ($extension == 'php') {
        echo '<i class="fa fa-code"></i>'.' '. 
             '<a href="readfile.php?file=' . $filePath . '" >'.$filename.'</a>'
        ;
        printFormDeleteFile($filePath);
    } else if (in_array($extension, IMAGE_FILE_EXTENSIONS)) {
        echo '<i class="fa fa-image"></i>'.' '
            .'<a href="viewImage.php?file=' . $filePath . '" >'.$filename.'</a>'
        ;
        printFormDeleteFile($filePath);
    } else {
        echo $filename;
        printFormDownloadFile($filePath);
        printFormDeleteFile($filePath);        
    }
    echo '<hr>';
}

function listFiles(string $directory, int $level = 0): void
{
    $dir = opendir($directory);
    if ($level == 0) {
        echo '<i class="fa fa-folder"></i>'.' '.$directory; 
        printFormCreateFolder($directory);
        printFormUploadFile($directory);
        printFormDeleteFolder($directory);
    }
    while ($filename = readdir($dir)) {
        if (is_dir($directory . '/' . $filename)) {
            listFolder($directory, $filename, $level);
        }

        if (is_file($directory . '/' . $filename)) {
            listFile($directory, $filename, $level);
        }
    }
    closedir($dir);
}

if (isset($_FILES['uploaded_file'])) {
    if ($_FILES['uploaded_file']['tmp_name']) {
        try {
            upload($_FILES['uploaded_file'], $_POST['directory'] );
        } catch (FileException $e) {
            echo $e->getMessage();
        } catch (\Exception $e) {
            echo "Įvyko klaida.";
        }
    } else {
        addFlashMessage('danger', 'Unable to upload file');
    }
}

if (isset($_POST['new_dir_name'])) {
    $folderPath = $_POST['newDirPath'] .'/'. $_POST['new_dir_name'];
    if ( !is_dir($folderPath) ){
        $folderCreated = makeNewDir($_POST['new_dir_name'], $_POST['newDirPath']);
        if ($folderCreated) {
            addFlashMessage('success', 'Folder is successfuly created!');
        }
    } else {
        addFlashMessage('danger', 'Folder already exists');
    }
}

if (isset($_POST['delete-dir'])) {
    if ( is_dir_empty($_POST['directory_to_delete']) ){
        deleteEmptyDir($_POST['directory_to_delete']);
        if (!is_dir($_POST['directory_to_delete'])) {
            addFlashMessage('success', 'Folder is deleted');
        } else {
            addFlashMessage('danger', 'Sorry, folder was not deleted');
        }
    } else {
        addFlashMessage('danger','Folder is not empty!');
    }
}

if (isset($_POST['file_to_delete'])) {
    $file = $_POST['file_to_delete'];
    $basePath = "C:/xampp/htdocs/php-project-1/";
    $deleteThis = $basePath . $file;
    deleteFile($deleteThis);
    if (!file_exists($deleteThis)) {
        addFlashMessage('success', 'File successfuly deleted');
    } else {
        addFlashMessage('danger', 'Sorry, file was not deleted');
    }
}

function makeNewDir($newdir, $newDirPath): bool
{
    return mkdir("$newDirPath/$newdir");
}

function deleteFile($dirToDelete): void
{
    unlink($dirToDelete);
}

function is_dir_empty($dir): bool 
{
    $handle = opendir($dir);
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            return FALSE;
        }
    }
    return TRUE;
}

function deleteEmptyDir($dirToDelete): void
{
    rmdir($dirToDelete);
}

function isFileSizeAllowed(array $file): bool
{
    return filesize($file['tmp_name']) < MAX_FILE_SIZE;
}

function isExtensionAllowed(array $file): bool
{
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

    return in_array($extension, ALLOWED_EXTENSIONS);
}

function filterFileName(string $oldFileName): string
{
    $result = filter_var($oldFileName, FILTER_SANITIZE_STRING);
    $result = str_replace(' ', '_', $result);
    $result = strtolower($result);
    $result = preg_replace('/[^a-z0-9_\.]/i', '', $result);

    return $result;
}

function upload(array $file, $uploadDirectory): void
{
    if (!isExtensionAllowed($file)) {
        addFlashMessage('danger', 'File extension is not allowed');
        return;
    }

    if (!isFileSizeAllowed($file)) {
        addFlashMessage('danger', 'File size is not allowed');
        return;
    }

    move_uploaded_file(
        $file['tmp_name'],
        $uploadDirectory . '/' . filterFileName($file['name'])
    );

    addFlashMessage('success', 'Successfully uploaded!');
}

function printFormCreateFolder($folderPath): void 
{      
    echo '<div class="form-create-folder"><form method="POST">
            <input style="display: none;" name="newDirPath" value="'.$folderPath.'">        
            <input type="text" placeholder="Įveskite pavadinimą" name="new_dir_name">
            <input type="submit" value="Sukurti aplanką" class="upload">
        </form></div>'
    ;
}        

function printFormUploadFile($folderPath): void  
{              
    echo '<div class="form-upload"><form method="post" enctype="multipart/form-data">
            <input style="display: none;" name="directory" value="'.$folderPath.'">
            <input type="file" name="uploaded_file">
            <input type="submit" value="Įkelti" class="upload">
        </form></div>'
    ;
}

function printFormDeleteFolder($folderPath): void 
{
    echo '<div class="form-delete-dir"><form method="POST">
            <input style="display: none;" name="directory_to_delete" value="'.$folderPath.'">
            <input type="submit" name="delete-dir" value="Ištrinti tuščią aplanką" class="upload">
        </form></div><hr>'
    ;
}

function printFormDownloadFile($filePath): void
{
    echo '<form class="download" action="download.php" method="GET">
            <input name="file_name" value="'. $filePath.'" style="display:none">
            <input type="submit" value="Atsisiųsti failą">
        </form>'
    ;
}

function printFormDeleteFile($filePath): void
{
    echo '<div class="form-delete-dir"><form method="POST">
            <input style="display: none;" name="file_to_delete" value="'.$filePath.'">
            <input type="submit" name="delete" value="Ištrinti failą" class="upload">
        </form></div>'
    ;
}