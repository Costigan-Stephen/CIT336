    <script src="/acme/js/passwordShow.js"></script> 

    <footer>
    <?php
    $year = date('Y');
    function auto_copyright($year = 'auto'){
        if(intval($year) == 'auto'){ $year = date('Y'); } 
        if(intval($year) == date('Y')){ echo intval($year); }
        if(intval($year) < date('Y')){ echo intval($year) . ' - ' . date('Y'); }
        if(intval($year) > date('Y')){ echo date('Y'); } 
    }
    ?>

    &copy; <?php echo date ('Y') ?> ACME, All rights reserved.<br>
    All images used are believed to be in "Fair Use". Please notify the author if any are not and they will be removed.<br>
    <?php
    // output e.g. 'Last modified: MonthName day year.'
    echo "Last modified: " . date ("F d Y.", getlastmod());
    ?>
    </footer>

</body>
</html>