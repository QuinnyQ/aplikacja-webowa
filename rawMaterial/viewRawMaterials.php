<?php
include '../templates/header.html';
include '../templates/conn.php';
?>
<div class="container">
    <div class="pageTitle">
        <h4>Stworzone surowce:</h4>
    </div>
    <?php
        $sql = "SELECT * FROM surowce";
        $result = $conn->query($sql);

        $sql1 = "SELECT id, nazwa, kategoria, jednostka FROM składniki";
        $result1 = $conn->query($sql1);
        
        $categories = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[$row["kategoria"]][] = $row;
            }
        }
        $units = [];
        $ids  = [];
        if ($result1->num_rows > 0) {
            while ($row1 = $result1->fetch_assoc()) {
                $units[$row1["nazwa"]] = $row1["jednostka"];
                $ids[$row1["nazwa"]] = $row1["id"];
            }
        }
        foreach ($categories as $category => $rawMaterials) {
            echo "<div class='category' id='$category' data-bs-toggle='tooltip' data-bs-title='Kliknij aby rozwinąć/zwinąć' onclick='showHide(\"$category" . "RawMaterials\")'>";
            echo "<span class='categoryColor form-control form-control-sm'>$category</span></div>";
            echo "<div id='$category" . "RawMaterials' class='detailsClose detailsColor'>";
            foreach ($rawMaterials as $rawMaterial) {
                echo "<div class='input-group inputSection' data-bs-toggle='tooltip' data-bs-title='Kliknij aby rozwinąć/zwinąć' id='rawMaterial" . $rawMaterial["id"] . "'>";
                echo "<label class='form-control form-control-sm idLabel'>" . $rawMaterial["id"] . "</label>";
                echo "<label class='form-control form-control-sm nameRawMaterialLabel' onclick='showHide(\"rawMaterial" . $rawMaterial["id"] . "Ingredients\")'>" . $rawMaterial["nazwa"] . "</label>";
                echo "<label class='form-control form-control-sm priceRawMaterialLabel'>" . $rawMaterial["cena"] . ' zł/t' . "</label>";
                echo "<a href='editRawMaterial.php?id=" . $rawMaterial["id"] . "' class='btn btn-sm btn-outline-secondary' type='button'>Edytuj</a>";
                echo "</div>";
                echo "<div id='rawMaterial" . $rawMaterial["id"] . "Ingredients' class='detailsClose'>";
                foreach ($rawMaterial as $key => $value) {
                    if (!in_array($key, ['id', 'kategoria', 'nazwa', 'cena'])) {
                        if($value > 0){
                            echo "<div class='input-group inputSection2'>";
                            echo "<label class='form-control form-control-sm idLabel'>" . $ids[$key] . "</label>";
                            echo "<label class='form-control form-control-sm nameWrap'>" . $key ."</label>";
                            echo "<label class='form-control form-control-sm amountRawMaterialLabel'>" . $value . " [" . $units[$key] . "]" ."</label>";
                            echo "</div>";
                        }
                    }
                }
                echo "</div>";
            }
            echo "</div>";
        }
    $conn->close();
    ?>
</div>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    function showHide(id) {
        var details = document.getElementById(id);
        var category = document.getElementById(id.replace("RawMaterials", ""));
        if (details.classList.contains("detailsClose")) {
            category.classList.add("open");
            details.classList.add("detailsOpen");
            details.classList.remove("detailsClose");
        } else {
            details.classList.add("detailsClose");
            details.classList.remove("detailsOpen");
            category.classList.remove("open");
        }
    }
</script>
</body>
</html>