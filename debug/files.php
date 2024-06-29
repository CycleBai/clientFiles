<?php
// 获取当前目录，如果没有提供则使用默认目录
$current_dir = isset($_GET['dir']) ? $_GET['dir'] : '.';

// 安全性检查，防止目录遍历攻击
$current_dir = realpath($current_dir);

// 确保目录存在并且是一个目录
if ($current_dir === false || !is_dir($current_dir)) {
    die("Invalid directory.");
}

// 读取目录内容
$files = scandir($current_dir);

?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP 文件浏览器</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>文件浏览器</h1>
    <p>当前目录: <?php echo htmlspecialchars($current_dir); ?></p>

    <table>
        <tr>
            <th>名称</th>
            <th>类型</th>
            <th>大小</th>
            <th>操作</th>
        </tr>

        <?php
        // 显示上一级目录链接
        if ($current_dir !== realpath('.')) {
            $parent_dir = dirname($current_dir);
            echo '<tr>';
            echo '<td colspan="4"><a href="?dir=' . urlencode($parent_dir) . '">上一级目录</a></td>';
            echo '</tr>';
        }

        // 遍历目录内容
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;

            $file_path = $current_dir . DIRECTORY_SEPARATOR . $file;
            $is_dir = is_dir($file_path);
            $file_type = $is_dir ? '目录' : '文件';
            $file_size = $is_dir ? '' : filesize($file_path);

            echo '<tr>';
            echo '<td>' . ($is_dir ? '<a href="?dir=' . urlencode($file_path) . '">' . htmlspecialchars($file) . '</a>' : htmlspecialchars($file)) . '</td>';
            echo '<td>' . $file_type . '</td>';
            echo '<td>' . $file_size . '</td>';
            echo '<td>' . ($is_dir ? '' : '<a href="' . htmlspecialchars($file_path) . '" download>下载</a>') . '</td>';
            echo '</tr>';
        }
        ?>

    </table>
</body>
</html>
