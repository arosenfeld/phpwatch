<?php
    interface DbObject
    {
        function loadById($id);
        function loadByRow($db_row);
        function saveToDb();
    }
?>
