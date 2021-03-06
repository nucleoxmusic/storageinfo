<?php
$url = "";
$root = "";
$file = "storageinfo.php";
/* Config */
ob_start();
clearstatcache();
$current_url = $url . $_SERVER['REQUEST_URI'];
$current = getcwd();

define("DS", DIRECTORY_SEPARATOR);
/*********************************************************************/

/* script version */
$version = "1.0";
$page_id = rand(1, 1000000);

if(!isset($_GET['page']) || empty($_GET['page'])) {
    header ("Location: storageinfo.php?page=$page_id");
}

?>




<!-------------------------------------------------------------------->
<!--  PHP scripts  --------------------------------------------------->
<?php

function folderSize($dir) {
    $count_size = 0;
    $count = 0;
    $dir_array = scandir($dir);
    foreach($dir_array as $key=>$filename){
        if($filename!=".." && $filename!="."){
            if(is_dir($dir."/".$filename)){
                $new_foldersize = foldersize($dir."/".$filename);
                $count_size = $count_size+ $new_foldersize;
            }else if(is_file($dir."/".$filename)){
                $count_size = $count_size + filesize($dir."/".$filename);
                $count++;
            }
        }
    }
    return $count_size;
}
function sizeFormat($bytes) {
    $len = strlen($bytes);
    $in  = "";
    if($len < 4) {
        $val = $bytes;
        $in = sprintf("%0.2f", $val);
        $int_val = intval($in);
        return setZeros($int_val). $in . " B";
    }
    if($len >= 4 && $len <=6) {
        $val = $bytes/1024;
        $in = sprintf("%0.2f", $val);
        $int_val = intval($in);
        return setZeros($int_val). $in . " K";
    }
    if($len >= 7 && $len <=9) {
        $val = $bytes/1024/1024;
        $in = sprintf("%0.2f", $val);
        $int_val = intval($in);
        return setZeros($int_val). $in . " M";
    }
    else {
        $val = $bytes/1024/1024/1024;
        $in = sprintf("%0.2f", $val);
        $int_val = intval($in);
        return setZeros($int_val). $in . " G";
    }
}
function sizeFormatInfo($bytes) {
    $len = strlen($bytes);
    $in  = "";
    if($len < 4) {
        $val = $bytes;
        $in = sprintf("%0.2f", $val);
        return $in . " B";
    }
    if($len >= 4 && $len <=6) {
        $val = $bytes/1024;
        $in = sprintf("%0.2f", $val);
        return $in . " Kb";
    }
    if($len >= 7 && $len <=9) {
        $val = $bytes/1024/1024;
        $in = sprintf("%0.2f", $val);
        return $in . " Mb";
    }
    else {
        $val = $bytes/1024/1024/1024;
        $in = sprintf("%0.2f", $val);
        return $in . " Gb";
    }

}
function setZeros($bytes) {
    $noZero = 1000;
    $oneZero = 999;
    $twoZero = 99;
    $threeZero = 9;


    if($bytes >= $noZero) {
        return "";
    } elseif($bytes <= $oneZero && $bytes >= $twoZero) {
        return '<h6 style="display: inline; color: transparent">0</h6>';
    } elseif($bytes <= $twoZero && $bytes > $threeZero) {
        return '<h6 style="display: inline; color: transparent">00</h6>';
    } elseif($bytes <= $threeZero) {
        return '<h6 style="display: inline; color: transparent">000</h6>';
    }
}
function detectByteType($filesize) {
    $output = 0;
    $string = strval($filesize);

    $b = 'B';$kb = 'K';$mb = 'M';$gb = 'G';

    if(strpos($string, $b) !== false){$output = 1;}
    elseif(strpos($string, $kb) !== false){$output = 2;}
    elseif(strpos($string, $mb) !== false){$output = 3;}
    elseif(strpos($string, $gb) !== false) {$output = 4;}

    return $output;
}
function detectType($extension) {

    switch ($extension) {

        // Audio, video and picture extensions
        case "aac":
        case "mp3":
        case "wav":
        case "mid":
            return "audio/" . $extension . "&nbsp" . "<i class='fa fa-audio-o'></i>";
            break;
        case "flv":
        case "mkv":
        case "mp4":
            return "video/" . $extension . "&nbsp" . "<i class='fa fa-file-movie-o'></i>";
            break;
        // Documents
        case "pdf":
            return "document/" . $extension . "&nbsp" . "<i class='fa fa-file-pdf-o'></i>";
            break;
        // Text information
        case "nfo":
        case "sfv":
        case "txt":
            return "Text/". $extension . "&nbsp" . "<i class='fa fa-book'></i>";
            break;
        case "dat":
            return "Text/". $extension . "&nbsp" . "<i class='fa fa-file-text-o'></i>";
            break;
        // DAW files
        case "reapeaks":
        case "reapindex":
        case "rpp":
            return "DAW_reaper/" . $extension . "&nbsp" . "<i class='fa fa-file'></i>";
            break;
        // Storage formats
        case "zip":
        case "tar.gz":
        case "rar":
        case "r00":
        case "r01":
        case "r02":
        case "r03":
        case "r04":
        case "r05":
        case "r06":
        case "r07":
        case "r08":
        case "r09":
        case "r10":
        case "r11":
        case "r12":
        case "r13":
        case "r14":
        case "r15":
            return "WinRar/" . $extension . "&nbsp" . "<i class='fa fa-file-archive-o'></i>";
            break;
        // Windows formats
        case "exe":
        case "dll":
        case "inf":
        case "efi":
            return "Windows/". $extension . "&nbsp" . "<i class='fa fa-windows'></i>";
            break;
        // Web extensions
        case "html":
        case "php":
        case "htm":
            return "Webdev/" . $extension . "&nbsp" .  "<i class='fa fa-html5'></i>";
            break;
        case "js":
        case "xml":
            return "Webdev/" . $extension . "&nbsp" .  "<i class='fa fa-code'></i>";
            break;
        case "css":
            return "Webdev/" . $extension . "&nbsp" .  "<i class='fa fa-css3'></i>";
            break;
        case "db":
            return "Webdev/" . $extension . "&nbsp" .  "<i class='fa fa-database'></i>";
            break;
            // Image formats
        case "jpg":
        case "JPG":
        case "jpeg":
        case "png":
        case "svg":
        case "gif":
            return "picture/" . $extension . "&nbsp" . "<i class='fa fa-picture-o'></i>";
            break;



        default:
            return "unknown" . "&nbsp" . "<i class='fa fa-file'></i>";
            break;
    }
}
function openDirectory($path, $url, $domain) {
    $div_totalsize = '';
    $folderCount = $fileCount = $entryCount = 0;
    $totalsize = 0;

    if ($handle = opendir($path)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != ".." && $entry !== "storageconfig.php" && $entry !== "storageinfo.php" && $entry !== ".htaccess") {
                if (is_dir($entry)) {
                    $current_folder_name = $entry;
                    $current_folder_size = sizeFormat(folderSize($entry));
                    $current_folder_size_info = sizeFormatInfo(folderSize($entry));
                    $bytetype = detectByteType($current_folder_size);
                    $date =  date ("d F - Y | g:ia", filemtime($entry));
                    $create =  date ("d F - Y | g:ia", filectime($entry));
                    $permissions = substr(sprintf('%o', fileperms("$entry")), -4);

                    $json_name = json_encode($entry);
                    $json_size = json_encode($current_folder_size_info);
                    $json_type = json_encode("Folder");
                    $json_date = json_encode($date);
                    $json_create = json_encode($create);
                    $json_perm = json_encode($permissions);

                    $php = $_SERVER['PHP_SELF'];
                    if(strcmp($php, "/storageinfo.php") == 0) {
                        $new_url  = $domain  . DS . $entry .$php;
                    } else {
                        $new_url  = $domain  . substr($php, 0 , -15) . $entry . DS . "storageinfo.php";
                    }



                    $div = '';

                    $div .= '<tr class="text-left tab-hover">';
                    $div .= "<td class='text-center text-break'>$current_folder_name</td>";
                    $div .= "<td class='text-right'><h6 style='display: inline; color: transparent'>$bytetype</h6>&nbsp$current_folder_size</td>";
                    $div .= "<td>Folder&nbsp<i class='fa fa-folder'></i></td>";
                    $div .= "<td>";
                    $div .= "<form class='btn-group' role='group' aria-label='controls'  style='width: 100%'>";
                    $div .= "<a class='btn btn-dark text-white' title='show info of folder' onclick='showFileInfo($json_name, $json_size, $json_type, $json_date, $json_perm)'><i class='fa fa-info'></i></a>";
                    $div .= "<a class='btn btn-dark text-white' href='$new_url' title='go to directory'><i class='fa fa-sign-in'></i></a>";
                    $div .= "<input type='hidden' name='foldername' value='$entry'>";
                    $div .= "<button type='submit' class='btn btn-dark text-white' style='' name='scanOnly' value='' onclick='showScanOnlyStart()' title='scan this folder only'><i class='fa fa-window-restore'></i></button>";
                    $div .= "</form>";
                    $div .= "</td>";
                    $div .= "</td>";
                    $div .= "</tr>";

                    echo $div;

                    $fileCount++;
                    $totalsize .= folderSize($entry);
                } else {
                    $current_file_name = $entry;
                    $current_file_size = sizeFormat(filesize($entry));
                    $current_file_size_info = sizeFormatInfo(filesize($entry));
                    $file_extension = pathinfo($entry, PATHINFO_EXTENSION);
                    $filetype = detectType($file_extension);
                    $bytetype = detectByteType($current_file_size);
                    $date =  date ("d F - Y | g:ia", filemtime($entry));
                    $create =  date ("d F - Y | g:ia", filectime($entry));
                    $permissions = substr(sprintf('%o', fileperms("$entry")), -4);

                    $json_name = json_encode($entry);
                    $json_size = json_encode($current_file_size_info);
                    $json_type = json_encode($file_extension);
                    $json_perm = json_encode($permissions);
                    $json_date = json_encode($date);
                    $json_create = json_encode($create);

                    $php = $_SERVER['PHP_SELF'];
                    if(strcmp($php, "/storageinfo.php") == 0) {
                        $new_url  = $domain  . DS . $entry;
                    } else {
                        $new_url  = $domain  . substr($php, 0 , -15) . $entry;
                    }
                    $div = '';

                    $div .= '<tr class="text-left tab-hover">';
                    $div .= "<td class='text-center text-break'>$current_file_name</td>";
                    $div .= "<td class='text-right'><h6 style='display: inline; color: transparent'>$bytetype</h6>&nbsp$current_file_size</td>";
                    $div .= "<td>$filetype</td>";
                    $div .= "<td>";
                    $div .= "<form class='btn-group' role='group' aria-label='controls' style='width: 100%'>";
                    $div .= "<a class='btn btn-dark text-white' style='' title='show info of file' onclick='showFileInfo($json_name,$json_size,$json_type, $json_date, $json_perm)'><i class='fa fa-info'></i></a>";
                    $div .= "<a class='btn btn-dark text-white' style=''  href='$new_url' title='see file on new tab' target='_blank'><i class='fa fa-eye'></i></a>";
                    $div .= "<input type='hidden' name='fileUnlink' value='$entry'>";
                    $div .= "<button type='submit' class='btn btn-dark text-white'  name='unlink' value='file' title='delete this file'><i class='fa fa-trash'></i></button>";
                    $div .= "</form>";
                    $div .= "</td>";
                    $div .= "</td>";
                    $div .= "</tr>";

                    echo $div;

                    $fileCount++;
                    $totalsize .= filesize($entry);
                }
                if($fileCount == 0) {
                    $div .= "<tr><th>No files on this directory</th></tr>";

                    echo $div;
                }
                $div = '';
                $entryCount++;
            }
        }
        closedir($handle);
    }
}
function recursiveScan($dir) {
    if(!empty($dir)) {
        $tree = glob(rtrim($dir, '/') . '/*');
        if (is_array($tree)) {
            foreach($tree as $file) {
                if (is_dir($file)) {
                    copy("storageinfo.php", "$file/storageinfo.php");
                    recursiveScan($file);
                }
            }
        }
    }
}
function recursiveScanDel($dir) {
    if(!empty($dir)) {
        $tree = glob(rtrim($dir, '/') . '/*');
        if (is_array($tree)) {
            foreach($tree as $file) {
                if (is_dir($file)) {
                    unlink("$file/storageinfo.php");
                    recursiveScan($file);
                }
            }
        }
    }
}
function deleteFile($file) {
    unlink($file);
}

function breadcrumb($location, $root, $url) {
    $separator = "&nbsp/&nbsp";
    $goto = $href = '';

    if(strcmp($location, $root) == 0) {

    } else {
        $root_length = strlen($root) + 1;
        $location_length = strlen($location);
        $new_cwd = substr($location, $root_length, $location_length);
        $taxo = explode(DS ,$new_cwd);
        $taxo_count = count($taxo);

        for($i = 0; $i <= $taxo_count; $i++) {
            $goto .= $taxo[$i] . DS;
            $href = $url . DS . $goto . DS . "storageinfo.php";
            $cmp_href = $root . DS . $goto;
            if(strcmp($cmp_href, $location) !== 1) {
                echo "<li class='breadcrumb-item'><a href='$href' class='a-hover' title='go back to $taxo[$i]' style='text-decoration: black; color: black'>$taxo[$i]</a></li>";
            } else {
                echo "<li class='breadcrumb-item'>$taxo[$i]</li>";
            }
        }
    }
}
/******************************************************************/

function readFileByLine($theFile, $theLine){
    /*Conviene establecer controles de la existencia del archivo*/

    $file = new SplFileObject($theFile);
    $file->seek($theLine);
    return $file->current();
}

/******************************************************************/
/* Scanning and indexing functions */
function indexerChecker($cwd, $root) {
    if(strcmp($cwd,$root) == 0) {
        echo "<script type='text/javascript'>$('#indexer').show()</script>";
    }
}
/******************************************************************/

function update($root, $url, $tmp) {
    $file_output = "";
    $_tmpOutput  = "";

    $root_update = $root;
    $url_update = $url;

    $archivo = fopen ($tmp,"r");
    $_tmpfile = fopen("_tmp.php", "w");



    $contador = 0;
    while (!feof($archivo)) {
        $linea = fgets($archivo, 75);
        if( ++$contador >  3 )
        {
            $_tmpOutput .= $linea;
        }
    }
    fclose ($archivo);

    $file_output .= '<?php';
    $file_output .= "\n";
    $file_output .= '$root = '."'$root_update'".';';
    $file_output .= '$url = '."'$url_update'".';';
    $file_output .= $_tmpOutput;

    fwrite($_tmpfile, $file_output);
    fclose($_tmpfile);
    unlink("storageinfo.php");
    copy("_tmp.php", "storageinfo.php");
    unlink($_tmpfile);
    unlink($tmp);
}

?>
<!-------------------------------------------------------------------->



<!---------------------------------------------------------------------------------->
<!--  Table sorting script  -->
<!--  NOTE: Don't change this unless you want to modify table sorting parameters  -->
<script>


    function initTable(theadCell)
    {
        var orderNumber=-theadCell.dataset.order||1,orderString,tableHTMLElement=theadCell;
        switch(orderNumber)
        {
            case 1:
            {
                orderString="🔺";
                break;
            }
            case -1:
            {
                orderString="🔻";
                break;
            }
        }
        while(tableHTMLElement.tagName!="TABLE")tableHTMLElement=tableHTMLElement.parentElement;
        if(!tableHTMLElement.dataset.scanned)
        {
            for(let localArray=[],rowIndexNumber=0,zeroRowSpanSet=new Set();rowIndexNumber<tableHTMLElement.tHead.rows.length;rowIndexNumber++)
            {
                if(!localArray[rowIndexNumber])localArray[rowIndexNumber]=[];
                for(let cellIndexNumber=0,localNumber=0;cellIndexNumber<tableHTMLElement.tHead.rows[rowIndexNumber].cells.length;cellIndexNumber++)
                {
                    while(localArray[rowIndexNumber][localNumber]||zeroRowSpanSet.has(localNumber))localNumber++;
                    tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].dataset.local=localNumber;
                    if(tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].rowSpan)
                    {
                        for(let scanRowNumber=0;scanRowNumber<tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].rowSpan;scanRowNumber++)
                        {
                            if(!localArray[rowIndexNumber+scanRowNumber])localArray[rowIndexNumber+scanRowNumber]=[];
                            for(let scanColumnNumber=0;scanColumnNumber<tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].colSpan;scanColumnNumber++)
                            {
                                localArray[rowIndexNumber+scanRowNumber][localNumber+scanColumnNumber]=true;
                            }
                        }
                    }
                    else
                    {
                        for(let scanColumnNumber=0;scanColumnNumber<tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].colSpan;scanColumnNumber++)
                        {
                            zeroRowSpanSet.add(localNumber+scanColumnNumber);
                        }
                    }
                    localNumber+=tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].colSpan;
                }
            }
            tbody:
                for(let tbodyIndexNumber=0;tbodyIndexNumber<tableHTMLElement.tBodies.length;tbodyIndexNumber++)
                {
                    for(let rowIndexNumber=0;rowIndexNumber<tableHTMLElement.tBodies[tbodyIndexNumber].rows.length;rowIndexNumber++)
                    {
                        for(let cellIndexNumber=0;cellIndexNumber<tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber].cells.length;cellIndexNumber++)
                        {
                            if(tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber].cells[cellIndexNumber].rowSpan!=1)continue tbody;
                        }
                    }
                    tableHTMLElement.tBodies[tbodyIndexNumber].dataset.sortable=true;
                }
            tableHTMLElement.dataset.scanned=true;
        }
        for(let rowIndexNumber=0;rowIndexNumber<tableHTMLElement.tHead.rows.length;rowIndexNumber++)
        {
            for(let cellIndexNumber=0;cellIndexNumber<tableHTMLElement.tHead.rows[rowIndexNumber].cells.length;cellIndexNumber++)
            {
                if(tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].dataset.local==theadCell.dataset.local&&tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].colSpan<=theadCell.colSpan)
                {
                    if(!tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].dataset.order)
                    {
                        tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].appendChild(document.createElement("font"));
                        tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].lastElementChild.color="red";
                        tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].lastElementChild.size=1;
                    }
                    else if(!+tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].dataset.order)tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].lastElementChild.hidden=false;
                    tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].dataset.order=orderNumber;
                    tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].lastElementChild.textContent=orderString;
                }
                else if(+tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].dataset.order)
                {
                    tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].dataset.order=0;
                    tableHTMLElement.tHead.rows[rowIndexNumber].cells[cellIndexNumber].lastElementChild.hidden=true;
                }
            }
        }
        return tableHTMLElement;
    }
    /**
     * @param {Number} order - -1 or +1
     * @returns {(left:Number,right:Number)=>Number}
     * if(left>right)return +number; //+number>0
     * if(left==right)return 0;
     * if(left<right)return -number; //-number<0
     */
    function getNumberComparatorFunction(order)
    {
        switch(order)
        {
            case 1:
            {
                return function(left,right)
                {
                    if(isNaN(left)||isNaN(right))
                    {
                        if(!isNaN(left))return -1;
                        if(!isNaN(right))return 1;
                        return 0;
                    }
                    else return left-right;
                }
            }
            case -1:
            {
                return function(left,right)
                {
                    if(isNaN(left)||isNaN(right))
                    {
                        if(!isNaN(left))return -1;
                        if(!isNaN(right))return 1;
                        return 0;
                    }
                    else return right-left;
                }
            }
        }
    }
    /**
     * @param {Number} order - -1 or +1
     * @param {String|String[]} [language=""] - IETF BCP 47 Language Tag
     * @param {Object} [options=null]
     *  - https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Collator
     * @param {Boolean} [useIntlCollator=false] - true or false
     * @returns {(left:String,right:String)=>Number}
     * if(left>right)return +number; //+number>0
     * if(left==right)return 0;
     * if(left<right)return -number; //-number<0
     */
    function getStringComparatorFunction(order,language,options,useIntlCollator)
    {
        switch(order)
        {
            case 1:
            {
                if(!language)return function(left,right)
                {
                    return left==right?0:left>right?1:-1;
                }
                else if(!useIntlCollator)return function(left,right)
                {
                    return left.localeCompare(right,language,options);
                }
                else
                {
                    const intlCollator=new Intl.Collator(language,options);
                    return function(left,right)
                    {
                        return intlCollator.compare(left,right);
                    }
                }
            }
            case -1:
            {
                if(!language)return function(left,right)
                {
                    return left==right?0:left<right?1:-1;
                }
                else if(!useIntlCollator)return function(left,right)
                {
                    return right.localeCompare(left,language,options);
                }
                else
                {
                    const intlCollator=new Intl.Collator(language,options);
                    return function(left,right)
                    {
                        return intlCollator.compare(right,left);
                    }
                }
            }
        }
    }
    /**
     * @param {HTMLTableCellElement} theadCell - <table> -> <thead> -> <td> or <th>
     * @param {String|String[]} [locales=""] - IETF BCP 47 Language Tag
     * @param {Object} [options=null]
     *  - https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Collator
     */
    function tableSort(theadCell,locales,options)
    {
        var tableComparatorFunction,tableHTMLElement=initTable(theadCell),tableLangString;
        if(!locales)
        {
            for(let node=tableHTMLElement;!tableLangString&&node.parentNode;node=node.parentNode)
            {
                tableLangString=node.lang;
            }
        }
        for(let tbodyIndexNumber=0;tbodyIndexNumber<tableHTMLElement.tBodies.length;tbodyIndexNumber++)
        {
            if(tableHTMLElement.tBodies[tbodyIndexNumber].dataset.sortable)
            {
                let rowInfoArray=[],tbodyComparatorFunction;
                if(locales)tbodyComparatorFunction=tableComparatorFunction||(tableComparatorFunction=getStringComparatorFunction(+theadCell.dataset.order,locales,options,true));
                else
                {
                    let tbodyLangString;
                    for(let node=tableHTMLElement.tBodies[tbodyIndexNumber];!tbodyLangString&&node.nodeName!="TABLE";node=node.parentNode)
                    {
                        tbodyLangString=node.lang;
                    }
                    if(tbodyLangString)tbodyComparatorFunction=getStringComparatorFunction(+theadCell.dataset.order,tbodyLangString,options);
                    else tbodyComparatorFunction=tableComparatorFunction||(tableComparatorFunction=getStringComparatorFunction(+theadCell.dataset.order,tableLangString,options,true));
                }
                for(let rowIndexNumber=0;rowIndexNumber<tableHTMLElement.tBodies[tbodyIndexNumber].rows.length;rowIndexNumber++)
                {
                    let cellIndexNumber=0,localNumber=0;
                    while(cellIndexNumber<tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber].cells.length&&localNumber+tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber].cells[cellIndexNumber].colSpan<=theadCell.dataset.local)
                    {
                        localNumber+=tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber].cells[cellIndexNumber].colSpan;
                        cellIndexNumber++;
                    }
                    rowInfoArray.push([tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber],{cellIndex:cellIndexNumber,local:localNumber}]);
                }
                rowInfoArray.sort(function(upRowInfo,downRowInfo)
                {
                    let downRowCellIndexNumber=downRowInfo[1].cellIndex,downRowLocalNumber=downRowInfo[1].local,upRowCellIndexNumber=upRowInfo[1].cellIndex,upRowLocalNumber=upRowInfo[1].local;
                    while(downRowCellIndexNumber<downRowInfo[0].cells.length&&upRowCellIndexNumber<upRowInfo[0].cells.length)
                    {
                        const resultNumber=tbodyComparatorFunction(upRowInfo[0].cells[upRowCellIndexNumber].innerText,downRowInfo[0].cells[downRowCellIndexNumber].innerText);
                        if(!resultNumber)
                        {
                            const downRowNextLocalNumber=downRowLocalNumber+downRowInfo[0].cells[downRowCellIndexNumber].colSpan,upRowNextLocalNumber=upRowLocalNumber+upRowInfo[0].cells[upRowCellIndexNumber].colSpan;
                            if(Math.min(downRowNextLocalNumber,upRowNextLocalNumber)>=+theadCell.dataset.local+theadCell.colSpan)return 0;
                            if(upRowNextLocalNumber<downRowNextLocalNumber)
                            {
                                upRowCellIndexNumber++;
                                upRowLocalNumber=upRowNextLocalNumber;
                            }
                            else if(upRowNextLocalNumber>downRowNextLocalNumber)
                            {
                                downRowCellIndexNumber++;
                                downRowLocalNumber=downRowNextLocalNumber;
                            }
                            else
                            {
                                upRowCellIndexNumber++;
                                downRowCellIndexNumber++;
                                upRowLocalNumber=upRowNextLocalNumber;
                                downRowLocalNumber=downRowNextLocalNumber;
                            }
                        }
                        else return resultNumber;
                    }
                    if(upRowCellIndexNumber<upRowInfo[0].cells.length)return -1;
                    if(downRowCellIndexNumber<downRowInfo[0].cells.length)return 1;
                    return 0;
                });
                rowInfoArray.forEach(function(rowInfo)
                {
                    tableHTMLElement.tBodies[tbodyIndexNumber].appendChild(rowInfo[0]);
                });
            }
        }
    }



    "use strict";
    /**
     * @param {String|String[]} [language=""] - IETF BCP 47 Language Tag
     * @param {Object} [options=null]
     *  - https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/NumberFormat
     * @param {Boolean} [useIntlCollator=false] - true or false
     * @returns {(number:Number)=>String}
     */
    function getNumberFormatFunction(language,options,useIntlCollator)
    {
        if(!language)return function(number)
        {
            return number.toString();
        }
        else if(!useIntlCollator)return function(number)
        {
            return number.toLocaleString(language,options);
        }
        else
        {
            const intlNumberFormat=new Intl.NumberFormat(language,options);
            return function(number)
            {
                return intlNumberFormat.format(number);
            }
        }
    }
    /**
     * @param {HTMLTableCellElement} theadCell - <table> -> <thead> -> <td> or <th>
     * @param {Boolean} [setTitle=false] - true or false
     * @param {String|String[]} [locales=""] - IETF BCP 47 Language Tag
     * @param {Object} [options=null]
     *  - https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Collator
     *  & https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/NumberFormat
     */
    function numberTableSort(theadCell,setTitle,locales,options)
    {
        var tableFormatFunction,tableHTMLElement=initTable(theadCell),tableNumberComparatorFunction=getNumberComparatorFunction(+theadCell.dataset.order),tableStringComparatorFunction,tableLangString;
        if(!locales)
        {
            for(let node=tableHTMLElement;!tableLangString&&node.parentNode;node=node.parentNode)
            {
                tableLangString=node.lang;
            }
        }
        for(let tbodyIndexNumber=0;tbodyIndexNumber<tableHTMLElement.tBodies.length;tbodyIndexNumber++)
        {
            let tbodyFormatFunction,tbodyLangString;
            if(tableHTMLElement.tBodies[tbodyIndexNumber].dataset.sortable)
            {
                let rowInfoArray=[],tbodyComparatorFunction;
                if(locales)tbodyComparatorFunction=tableStringComparatorFunction||(tableStringComparatorFunction=getStringComparatorFunction(+theadCell.dataset.order,locales,options,true));
                else
                {
                    for(let node=tableHTMLElement.tBodies[tbodyIndexNumber];!tbodyLangString&&node.nodeName!="TABLE";node=node.parentNode)
                    {
                        tbodyLangString=node.lang;
                    }
                    if(tbodyLangString)tbodyComparatorFunction=getStringComparatorFunction(+theadCell.dataset.order,tbodyLangString,options);
                    else tbodyComparatorFunction=tableStringComparatorFunction||(tableStringComparatorFunction=getStringComparatorFunction(+theadCell.dataset.order,tableLangString,options,true));
                }
                for(let rowIndexNumber=0;rowIndexNumber<tableHTMLElement.tBodies[tbodyIndexNumber].rows.length;rowIndexNumber++)
                {
                    let cellIndexNumber=0,localNumber=0;
                    while(cellIndexNumber<tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber].cells.length&&localNumber+tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber].cells[cellIndexNumber].colSpan<=theadCell.dataset.local)
                    {
                        localNumber+=tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber].cells[cellIndexNumber].colSpan;
                        cellIndexNumber++;
                    }
                    rowInfoArray.push([tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber],{cellIndex:cellIndexNumber,local:localNumber}]);
                }
                if(setTitle)
                {
                    if(locales)tbodyFormatFunction=tableFormatFunction||(tableFormatFunction=getNumberFormatFunction(locales,options,true));
                    else if(tbodyLangString)tbodyFormatFunction=getNumberFormatFunction(tbodyLangString,options);
                    else tbodyFormatFunction=tableFormatFunction||(tableFormatFunction=getNumberFormatFunction(tableLangString,options,true));
                    rowInfoArray.forEach(function(rowInfo)
                    {
                        for(let cellIndexNumber=rowInfo[1].cellIndex,localNumber=rowInfo[1].local;cellIndexNumber<rowInfo[0].cells.length&&localNumber<+theadCell.dataset.local+theadCell.colSpan;cellIndexNumber++)
                        {
                            if(!isNaN(rowInfo[0].cells[cellIndexNumber].innerText))rowInfo[0].cells[cellIndexNumber].title=tbodyFormatFunction(+rowInfo[0].cells[cellIndexNumber].innerText);
                            localNumber+=rowInfo[0].cells[cellIndexNumber].colSpan;
                        }
                    });
                }
                rowInfoArray.sort(function(upRowInfo,downRowInfo)
                {
                    let downRowCellIndexNumber=downRowInfo[1].cellIndex,downRowLocalNumber=downRowInfo[1].local,upRowCellIndexNumber=upRowInfo[1].cellIndex,upRowLocalNumber=upRowInfo[1].local;
                    while(downRowCellIndexNumber<downRowInfo[0].cells.length&&upRowCellIndexNumber<upRowInfo[0].cells.length)
                    {
                        const resultNumber=isNaN(downRowInfo[0].cells[downRowCellIndexNumber].innerText)&&isNaN(upRowInfo[0].cells[upRowCellIndexNumber].innerText)?tbodyComparatorFunction(upRowInfo[0].cells[upRowCellIndexNumber].innerText,downRowInfo[0].cells[downRowCellIndexNumber].innerText):tableNumberComparatorFunction(+upRowInfo[0].cells[upRowCellIndexNumber].innerText,+downRowInfo[0].cells[downRowCellIndexNumber].innerText);
                        if(!resultNumber)
                        {
                            const downRowNextLocalNumber=downRowLocalNumber+downRowInfo[0].cells[downRowCellIndexNumber].colSpan,upRowNextLocalNumber=upRowLocalNumber+upRowInfo[0].cells[upRowCellIndexNumber].colSpan;
                            if(Math.min(downRowNextLocalNumber,upRowNextLocalNumber)>=+theadCell.dataset.local+theadCell.colSpan)return 0;
                            if(upRowNextLocalNumber<downRowNextLocalNumber)
                            {
                                upRowCellIndexNumber++;
                                upRowLocalNumber=upRowNextLocalNumber;
                            }
                            else if(upRowNextLocalNumber>downRowNextLocalNumber)
                            {
                                downRowCellIndexNumber++;
                                downRowLocalNumber=downRowNextLocalNumber;
                            }
                            else
                            {
                                upRowCellIndexNumber++;
                                downRowCellIndexNumber++;
                                upRowLocalNumber=upRowNextLocalNumber;
                                downRowLocalNumber=downRowNextLocalNumber;
                            }
                        }
                        else return resultNumber;
                    }
                    if(upRowCellIndexNumber<upRowInfo[0].cells.length)return -1;
                    if(downRowCellIndexNumber<downRowInfo[0].cells.length)return 1;
                    return 0;
                });
                rowInfoArray.forEach(function(rowInfo)
                {
                    tableHTMLElement.tBodies[tbodyIndexNumber].appendChild(rowInfo[0]);
                });
            }
            else if(setTitle)
            {
                if(locales)tbodyFormatFunction=tableFormatFunction||(tableFormatFunction=getNumberFormatFunction(locales,options,true));
                else
                {
                    for(let node=tableHTMLElement.tBodies[tbodyIndexNumber];!tbodyLangString&&node.nodeName!="TABLE";node=node.parentNode)
                    {
                        tbodyLangString=node.lang;
                    }
                    if(tbodyLangString)tbodyFormatFunction=getNumberFormatFunction(tbodyLangString,options);
                    else tbodyFormatFunction=tableFormatFunction||(tableFormatFunction=getNumberFormatFunction(tableLangString,options,true));
                }
                for(let localArray=[],rowIndexNumber=0,zeroRowSpanSet=new Set();rowIndexNumber<tableHTMLElement.tBodies[tbodyIndexNumber].rows.length;rowIndexNumber++)
                {
                    let cellIndexNumber=0,localNumber=0;
                    if(!localArray[rowIndexNumber])localArray[rowIndexNumber]=[];
                    while(localArray[rowIndexNumber][localNumber]||zeroRowSpanSet.has(localNumber))localNumber++;
                    while(cellIndexNumber<tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber].cells.length&&localNumber<+theadCell.dataset.local+theadCell.colSpan)
                    {
                        if(localNumber+tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber].cells[cellIndexNumber].colSpan>theadCell.dataset.local&&!isNaN(tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber].cells[cellIndexNumber].innerText))tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber].cells[cellIndexNumber].title=tbodyFormatFunction(+tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber].cells[cellIndexNumber].innerText);
                        if(tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber].cells[cellIndexNumber].rowSpan)
                        {
                            for(let scanRowNumber=0;scanRowNumber<tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber].cells[cellIndexNumber].rowSpan;scanRowNumber++)
                            {
                                if(!localArray[rowIndexNumber+scanRowNumber])localArray[rowIndexNumber+scanRowNumber]=[];
                                for(let scanColumnNumber=0;scanColumnNumber<tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber].cells[cellIndexNumber].colSpan;scanColumnNumber++)
                                {
                                    localArray[rowIndexNumber+scanRowNumber][localNumber+scanColumnNumber]=true;
                                }
                            }
                        }
                        else
                        {
                            for(let scanColumnNumber=0;scanColumnNumber<tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber].cells[cellIndexNumber].colSpan;scanColumnNumber++)
                            {
                                zeroRowSpanSet.add(localNumber+scanColumnNumber);
                            }
                        }
                        localNumber+=tableHTMLElement.tBodies[tbodyIndexNumber].rows[rowIndexNumber].cells[cellIndexNumber].colSpan;
                        while(localArray[rowIndexNumber][localNumber]||zeroRowSpanSet.has(localNumber))localNumber++;
                        cellIndexNumber++;
                    }
                }
            }
        }
        if(tableHTMLElement.tFoot&&setTitle)
        {
            let tfootFormatFunction,tfootLangString;
            if(locales)tfootFormatFunction=tableFormatFunction||getNumberFormatFunction(locales,options);
            else
            {
                for(let node=tableHTMLElement.tFoot;!tfootLangString&&node.nodeName!="TABLE";node=node.parentNode)
                {
                    tfootLangString=node.lang;
                }
                if(tfootLangString)tfootFormatFunction=getNumberFormatFunction(tfootLangString,options);
                else tfootFormatFunction=tableFormatFunction||getNumberFormatFunction(tableLangString,options);
            }
            for(let localArray=[],rowIndexNumber=0,zeroRowSpanSet=new Set();rowIndexNumber<tableHTMLElement.tFoot.rows.length;rowIndexNumber++)
            {
                let cellIndexNumber=0,localNumber=0;
                if(!localArray[rowIndexNumber])localArray[rowIndexNumber]=[];
                while(localArray[rowIndexNumber][localNumber]||zeroRowSpanSet.has(localNumber))localNumber++;
                while(cellIndexNumber<tableHTMLElement.tFoot.rows[rowIndexNumber].cells.length&&localNumber<+theadCell.dataset.local+theadCell.colSpan)
                {
                    if(localNumber+tableHTMLElement.tFoot.rows[rowIndexNumber].cells[cellIndexNumber].colSpan>theadCell.dataset.local&&!isNaN(tableHTMLElement.tFoot.rows[rowIndexNumber].cells[cellIndexNumber].innerText))tableHTMLElement.tFoot.rows[rowIndexNumber].cells[cellIndexNumber].title=tfootFormatFunction(+tableHTMLElement.tFoot.rows[rowIndexNumber].cells[cellIndexNumber].innerText);
                    if(tableHTMLElement.tFoot.rows[rowIndexNumber].cells[cellIndexNumber].rowSpan)
                    {
                        for(let scanRowNumber=0;scanRowNumber<tableHTMLElement.tFoot.rows[rowIndexNumber].cells[cellIndexNumber].rowSpan;scanRowNumber++)
                        {
                            if(!localArray[rowIndexNumber+scanRowNumber])localArray[rowIndexNumber+scanRowNumber]=[];
                            for(let scanColumnNumber=0;scanColumnNumber<tableHTMLElement.tFoot.rows[rowIndexNumber].cells[cellIndexNumber].colSpan;scanColumnNumber++)
                            {
                                localArray[rowIndexNumber+scanRowNumber][localNumber+scanColumnNumber]=true;
                            }
                        }
                    }
                    else
                    {
                        for(let scanColumnNumber=0;scanColumnNumber<tableHTMLElement.tFoot.rows[rowIndexNumber].cells[cellIndexNumber].colSpan;scanColumnNumber++)
                        {
                            zeroRowSpanSet.add(localNumber+scanColumnNumber);
                        }
                    }
                    localNumber+=tableHTMLElement.tFoot.rows[rowIndexNumber].cells[cellIndexNumber].colSpan;
                    while(localArray[rowIndexNumber][localNumber]||zeroRowSpanSet.has(localNumber))localNumber++;
                    cellIndexNumber++;
                }
            }
        }
    }


</script>
<!---------------------------------------------------------------------------------->




<!-------------------------------------------------------------------->
<!--  dedicated scripts (change this to modify page scripts)  -------->
<script type="text/javascript">

    /* This script shows the info of selected file on info table */
    function showFileInfo(filename, filesize, filetype, lastdate, permissions) {
        $("#filename").html(filename);
        $("#filesize").html(filesize);
        $("#filetype").html(filetype);
        $("#permissions").html(permissions);
        $("#lastdate").html(lastdate);
    }
    /*************************************************************/
    /* This scripts show the respective test to each indexing option */
    function  showScanStart() {
        $("#status").html("<b>Scan on progress, please wait...</b>");
    }
    function  showScanOnlyStart() {
        $("#status").html("<b>Scan on selected folder, please wait...</b>");
    }
    function  showUpdate() {
        $("#status").html("<b>Script update started, please wait...</b>");
    }
    function  showUpdateCur() {
        $("#status").html("<b>Script update on progress</b>");
    }
    function  deleteIndexing() {
        $("#status").html("</b>Indexing is being deleted from disk, please wait...</b>");
    }
    /*****************************************************************/
    /* This script shows the error card if there's errors on the script */
    function showErrorConfigMessage() {
        $("#errorConfigCard").show();
    }
    function showSucesConfigMessage() {
        $("#sucesConfigCard").show();
    }
    function  updateSucesssMessage() {
        $("#updateSuccessCard").show();
    }
    function delefeFileMsg() {
        $("#deleteFileCard").show();
    }
    /********************************************************************/
    function clearList() {
        $("#list-files").html("");
    }

</script>
<!-------------------------------------------------------------------->





<!-------------------------------------------------------------------->
<!--  CSS stylesheet  ------------------------------------------------>
<style>

    /* ---------------------------------------------------------- */
    /* ----                BASIC CONFIG CSS                  ---- */
    /* ---------------------------------------------------------- */

    @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');
    * {
        margin: 0;
        padding: 0;
        outline: none;
        box-sizing: border-box;
        text-decoration: none;
        font-family: 'Montserrat', sans-serif;
    }
    body {
        height: 100%;
        width: 100%;
    }

    *::-webkit-scrollbar{
        width:12px;
        background-color:#cccccc;
    }
    *::-webkit-scrollbar:horizontal{
        height:12px;
    }
    *::-webkit-scrollbar-track{
        border:1px #787878 solid;
        -webkit-box-shadow:0 0 6px #c8c8c8 inset;
    }
    *::-webkit-scrollbar-thumb{
        background-color:#212529;
        transition: all 0.2s;
    }
    *::-webkit-scrollbar-thumb:hover{
        background-color:#212529;
        border:1px solid #333333;
    }
    *::-webkit-scrollbar-thumb:active{
        background-color:#212529;
        border:1px solid #333333;
    }

    /* ---------------------------------------------------------- */
    /* ----                STANDARD CLASSES                  ---- */
    /* ---------------------------------------------------------- */

    .text-white {
        color: white;
        text-decoration: white;
    }
    .text-black {
        color: white;
        text-decoration: white;
    }
    .text-center {
        text-align: center;
    }
    .text-left {
        text-align: left;
    }
    .text-right {
        text-align: right;
    }
    .head-text {
        padding: 10px;
        font-size: 24px;
    }
    .info-head-text {
        padding: 30px;
        font-size: 30px;
    }
    thead th[onclick],thead td[onclick] {
        cursor:pointer;
    }
    .info-card {
        background: #343a40;
        color: white;
        padding: 20px;
        border-radius: 40px
    }
    .info-card:hover {
        background: #333333;
    }

    /* ---------------------------------------------------------- */
    /* ----                CONTAINER STYLE                   ---- */
    /* ---------------------------------------------------------- */

    .list-cont {
        height:  90%;
        width: 75%;
        background: white;
        position: absolute;
        overflow-y: auto;
        margin-top: 10vh;
    }
    /* --  Header with folder name -- */
    .list-cont .header {
        background: #212529;
        text-align: center;
        color: white;
        height: 10vh;
    }
    /* ------------------------------ */
    .info-cont {
        height: 90%;
        width: 25%;
        background: rgba(0,0,0,0.74);
        position: absolute;
        right: 0;
        margin-top: 10vh;
        overflow-y: auto;
    }

    table.dataTable thead .sorting:after,
    table.dataTable thead .sorting:before,
    table.dataTable thead .sorting_asc:after,
    table.dataTable thead .sorting_asc:before,
    table.dataTable thead .sorting_asc_disabled:after,
    table.dataTable thead .sorting_asc_disabled:before,
    table.dataTable thead .sorting_desc:after,
    table.dataTable thead .sorting_desc:before,
    table.dataTable thead .sorting_desc_disabled:after,
    table.dataTable thead .sorting_desc_disabled:before {
        bottom: .5em;
    }


    /* ---------------------------------------------------------- */
    /* ----                INFORMATION STYLE                 ---- */
    /* ---------------------------------------------------------- */

    .filename {
        width: 100%;
        background: #343a40;
        height: 18.9%;
    }




</style>
<!-------------------------------------------------------------------->









<!--------------------------- HTML page ------------------------------->

<?php

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
        <meta http-equiv="pragma" content="no-cache" />

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Storage information</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    </head>
    <body>
            <div class="list-cont" id="list-cont">
                <div class="header fixed-top">
                    <nav aria-label="breadcrumb" class="text-center" style="display: flex; justify-content: center;font-size: 30px; color: black">
                        <ol class="breadcrumb" style="background: rgba(250,250,250,0.8); border-radius: 30px">
                            <li class="breadcrumb-item">
                                <div class='btn-group' role='group' aria-label='directory options' style='display: inline'>
                                    <a type='button' class='btn' style="cursor: pointer" data-toggle='modal' data-target='#info' title="About this script"><i class="fa fa-info-circle fa-2x"></i></a>
                                    <a class="btn" style="cursor: pointer" onclick="window.location.href = 'storageinfo.php'" title="refresh listing"><i class="fa fa-refresh fa-2x"></i></a>
                                    <a class="btn" style="cursor: pointer" onclick="window.location.href = '<?php echo $url.DS."storageinfo.php" ?>'" style="text-decoration: white; color: white" title="Go to root directory"><i class="fa fa-home fa-2x"></i></a>
                                </div>
                            </li>
                            <?php breadcrumb($current, $root, $url) ?>
                        </ol>
                    </nav>
                </div>
                <table class="sortable table table-hover" id="list-table">
                    <thead>
                        <tr class="thead-dark text-left">
                            <th class="text-center head-text th-sm" width="65%" onclick="tableSort(this)">File name</th>
                            <th class="text-right head-text" tabindex="-1" onclick="numberTableSort(this,true,'en')" width="10%">Size</th>
                            <th class="head-text" width="15%" onclick="tableSort(this)">File type</th>
                            <th class="head-text" width="10%">Options</th>
                        </tr>
                    </thead>
                    <tbody id="list-files">
                        <?php
                        openDirectory(getcwd(), $current_url, $url);
                        ?>
                    </tbody>
                </table>

                <div class="modal" id="config" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin-top: 10vh">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header text-center">
                                <h5 class="modal-title" id="exampleModalLabel" style="text-align: center">Configuration&nbsp<i class="fa fa-cog"></i></h5>
                            </div>
                            <div class="modal-body">
                                <form method="get">
                                    <div class="form-group">
                                        <label for="root" class="text-break"><h2 class="">Root folder&nbsp<i class="fa fa-home"></i></h2></label>
                                        <h3>Current: <b class="text-muted"><?php echo $root ?></b></h3>
                                        <input type="text" class="form-control" id="root" placeholder="new root folder" name="root">
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label for="url" class="text-break"><h2 class="">Domain name&nbsp<i class="fa fa-link"></i></h2></label>
                                        <h3>Current: <b class="text-muted"><?php echo $url ?></b></h3>
                                        <input type="text" class="form-control" id="url" placeholder="new domain url" name="url">
                                    </div>
                                    <hr>
                                    <div class="from-group" style="display: flex; justify-content: space-around">
                                        <button type="submit" class="btn btn-outline-dark" name="configSave"><i class="fa fa-save"></i>&nbspSave changes</button>
                                        <input type="hidden" value="<?php echo $page_id ?>" name="page">
                                        <button type="reset" class="btn btn-outline-dark" name="configReset"><i class="fa fa-eraser"></i>&nbspClear all</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal" id="info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true" style="margin-top: 10vh">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-dark text-white">
                            <h1 class="modal-title" id="exampleModalLabel" style="text-align: center">About Storage Info&nbsp<i class="fa fa-info-circle"></i></h1>
                        </div>
                        <div class="modal-body">
                            <h4>Created by Nucleox</h4>
                            <h6 style="margin-top: 5px; display: inline">Version of script: &nbsp<h5 style="display: inline"><b class="text-monospace"><?php echo $version ?></b></h5></h6>
                            <hr>
                            <div class="info-card">
                                <i class="fa fa-github fa-3x d-inline" style="vertical-align: middle"></i>
                                <a href="https://github.com/nucleoxmusic/storageinfo" target="_blank" style="text-decoration: white; color: white"><h5 class="d-inline">&nbsp&nbsp&nbspOfficial Github repository</h5></a>
                            </div>
                            <br>
                            <div class="info-card">
                                <i class="fa fa-dropbox fa-3x d-inline" style="vertical-align: middle"></i>
                                <a href="https://www.dropbox.com/sh/feoyq4rxhgippk8/AAA7Tey1Cjuf53P8r9lTUikLa?dl=0" target="_blank" style="text-decoration: white; color: white"><h5 class="d-inline">&nbsp&nbsp&nbspDropbox software vault</h5></a>
                            </div>
                            <br>
                            <div class="info-card">
                                <i class="fa fa-google fa-3x d-inline" style="vertical-align: middle"></i>
                                <a href="https://drive.google.com/drive/folders/1MGZ6V5Dw6GSoscfelaMAlK_4sNBfVExm?usp=sharing" target="_blank" style="text-decoration: white; color: white"><h5 class="d-inline">&nbsp&nbsp&nbspGoogle drive CDN</h5></a>
                            </div>
                        </div>
                        <div class="modal-footer bg-dark">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-cont">

                <table class="table">
                    <thead class="thead-light text-white text-center">
                        <tr style="height: 100px">
                            <th>Information of <h3 id="filename" class="text-break">No file selected</h3></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-secondary"><th><bold class="d-inline">Size:</bold>&nbsp&nbsp&nbsp<div id="filesize" class="text-left d-inline"> --- </div></th></tr>
                        <tr class="table-secondary"><th><bold class="d-inline">Type:</bold>&nbsp&nbsp&nbsp<div id="filetype" class="text-left d-inline"> --- </div></th></tr>
                        <tr class="table-secondary"><th><bold class="d-inline">Permissions:</bold>&nbsp&nbsp&nbsp<div id="permissions" class="text-left d-inline"> --- </div></th></tr>
                        <tr class="table-secondary"><th><bold class="d-inline">Date modified:</bold>&nbsp&nbsp&nbsp<div id="lastdate" class="text-left d-inline"> --- </div></th></tr>
                    </tbody>
                </table>
                <br>

                <?php

                clearstatcache();




                ?>

                <div style="padding-left: 10px; padding-right: 10px; display: none" id="indexer">
                    <div style="" class="card">
                        <div class="card-header">
                            <h4 class="card-title" style="display: inline">Scan and index options</h4>&nbsp&nbsp&nbsp<i style="display: inline"><b>version: <?php echo $version ?></b></i>
                        </div>
                        <div class="card-body">
                            <form method="get">
                                <div class="btn-group" style="width: 100%">
                                    <form method="get" style="">
                                        <input type="hidden" value="<?php echo $page_id ?>" name="page">
                                        <button type='button' class='btn btn-dark d-inline' data-toggle='modal' data-target='#Config' style="width: 10%; text-align: center; vertical-align: middle" title="Open configuration"><i class="fa fa-cog"></i></button>
                                        <button type="submit" class="btn btn-outline-dark" style="width: 90%" name="scan" value="true" onclick="showScanStart()">Start scan</button>
                                        <button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle-split" style="width: 10%" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    </form>


                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header">More options</h6>
                                        <form method="get">
                                            <input type="hidden" value="<?php echo $page_id ?>" name="page">
                                            <button type="submit" class="dropdown-item btn btn-outline-dark" name="delete" value="true" onclick="deleteIndexing()">Delete indexing</button>
                                        </form>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer" id="card-footer">
                            <div class="card-subtitle">Status:</div>
                            <div class="" role="alert" id="status"></div>
                            <?php
                            if(isset($_GET['scan'])) {
                                clearstatcache();
                                recursiveScan($current);
                                echo "<b>Index has finished, <a href='$url/storageinfo.php?page=$page_id'>here</a> to go back<br></b>";
                            }
                            if(isset($_GET['scanOnly'])) {
                                $file = $_GET['foldername'];
                                copy("storageinfo.php", "$file/storageinfo.php");
                                header("Location: storageinfo.php");
                            }
                            if(isset($_GET['delete'])) {
                                clearstatcache();
                                recursiveScanDel($current);
                                echo "<b>Indexing has been deleted from disk, <a href='storageinfo.php?page=$page_id'>click here</a> to go back</b>";
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                    clearstatcache();
                    indexerChecker($current, $root); ?>
                </div>

                <br>
                <div class="card" id="errorConfigCard" style="padding-left: 10px; padding-right: 10px;display: none">
                    <div class="card-body">
                        <h4 class="card-title">Error on configuration detected!</h4>
                        <hr>
                        <p class="card-text">
                            <?php

                            if(empty($root) || empty($url)) {
                                clearstatcache();
                                echo"Some necessary config parameters are missing from the config lines, add it to continue using script without further errors. <br><br> <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#Config'>Press here to configure</button><br>";
                                echo "<script type='text/javascript'> showErrorConfigMessage() </script>";
                            }

                            ?>
                        </p>
                    </div>
                </div>

                <div class="card" id="sucesConfigCard" style="padding-left: 10px; padding-right: 10px; margin-top: 40px;display: none">
                    <div class="card-body">
                        <h4 class="card-title">Configuration succesfully applied!</h4>
                        <hr>
                        <p class="card-text">
                            <?php

                            if(isset($_GET['configSave'])) {
                                clearstatcache();
                                $new_root = $_GET['root'];
                                $new_url = $_GET['url'];

                                if(!empty($new_root) || !empty($new_url)) {
                                    $archivo = fopen ("storageinfo.php","r");
                                    $_tmpfile = fopen("_tmp.php", "w");
                                    $theFile="storageinfo.php";

                                    $root_line = readFileByLine($theFile, 1);
                                    $url_line = readFileByLine($theFile, 2);


                                    $contador = 0;
                                    while (!feof($archivo)) {
                                        $linea = fgets($archivo, 75);
                                        if( ++$contador > 3 )
                                        {
                                            $_tmpOutput .= $linea;
                                        }
                                    }
                                    fclose ($archivo);

                                    $file_output .= '<?php';
                                    $file_output .= "\n";
                                    $file_output .= '$root = '."'$new_root'".';';
                                    $file_output .= '$url = '."'$new_url'".';';
                                    $file_output .= $_tmpOutput;

                                    fwrite($_tmpfile, $file_output);
                                    fclose($_tmpfile);
                                    unlink("storageinfo.php");
                                    rename("_tmp.php", "storageinfo.php");

                                    copy("storageinfo.php", "$new_root/storageinfo.php");

                                    echo "<script type='text/javascript'> showSucesConfigMessage() </script>";
                                    echo "<b>Changes succesfully applied, <a href='$new_url/storageinfo.php?page=$page_id'> click here</a> to go to new location, after redirecting scan to update all scripts copied on disk</b>";
                                } else {
                                    header("Location: storageinfo.php");
                                }
                            }

                            ?>
                        </p>
                    </div>
                </div>

                <div class="card" id="updateSuccessCard" style="padding-left: 10px; padding-right: 10px; display: none">
                    <div class="card-body">
                        <h4 class="card-title">Update results:</h4>
                        <hr>
                        <p class="card-text">
                            <?php

                            if(isset($_GET['update'])) {
                                clearstatcache();
                                echo "<script type='text/javascript'> showUpdateCur() </script>";

                                $update = $_GET['update'];
                                $tmpName = "download_tmp.php";

                                echo "<script type='text/javascript'> updateSucesssMessage() </script>";

                                if(copy("https://carlosarellano.com/cdndelivery/storageinfo.php", "$tmpName")) {
                                    clearstatcache();
                                    update($root, $url, $tmpName);
                                    echo "<b>Update succesfully retrieved from repository,  <br><br> <a type='button' class='btn btn-primary' href='storageinfo.php?scan=true&&page=$page_id' onclick='showScanStart()' style='color: white'>Proceed here to rescan</a><br></b>";
                                } else {
                                    echo "<b>There was an error updating, <a href='storageinfo.php?update=true&&page=$page_id'>click here</a> to retry</b>";
                                }
                            }

                            ?>
                        </p>
                    </div>
                </div>

                <br>
                <div class="card" id="deleteFileCard" style="padding-left: 10px; padding-right: 10px;display: none">
                    <div class="card-body">
                        <h4 class="card-title">File deleted sucessfully</h4>
                        <hr>
                        <p class="card-text">
                            <?php

                            if(isset($_GET['unlink'])) {
                                echo "<script type='text/javascript'> delefeFileMsg() </script>";
                                clearstatcache();
                                $file = $_GET['fileUnlink'];
                                deleteFile($file);
                                echo "<b>$file has been deleted, <a href='storageinfo.php?page=$page_id'>click here</a> to go to listing and then press F5 to refresh scan</b>";
                            }

                            ?>
                        </p>
                    </div>
                </div>
            </div>
    </body>
</html>
