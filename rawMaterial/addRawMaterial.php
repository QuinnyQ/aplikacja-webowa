<?php
include '../templates/header.html';
include '../templates/conn.php';
?>
  <div class="container">
        <div class="pageTitle">
            <h4>Dodaj surowiec:</h4>
        </div>
        <form action="addRawMaterial.php" method="post">
                <div>
                    <div class="input-group mb-1">
                        <label class="form-control form-control-sm tags nameLabel radiusRemove">Nazwa</label>
                        <input type="text" class="form-control form-control-sm radiusRemove" maxlength="50" required name="nazwa">
                    </div>
                    <div class="input-group mb-1">
                        <label class="form-control form-control-sm tags nameLabel radiusRemove">Cena</label>
                        <input type="number" min="0" max="99999" step="0.01" class="form-control form-control-sm" name="cena" required>
                        <label class="form-control form-control-sm tags unitLabel">zł/t</label>
                    </div>
                    <select id="kategoria" name="kategoria" class="form-control form-control-sm radiusRemove" required>
                        <option selected value="">Wybierz kategorię</option>
                        <option value="Ziarna i nasiona">Ziarna i nasiona</option>
                        <option value="Pasze przemysłowe pochodzenia roślinnego">Pasze przemysłowe pochodzenia roślinnego</option>
                        <option value="Pasze przemysłowe pochodzenia zwierzęcego">Pasze przemysłowe pochodzenia zwierzęcego</option>
                        <option value="Susze z roślin zielonych">Susze z roślin zielonych</option>
                        <option value="Zielonki">Zielonki</option>
                        <option value="Kiszonki">Kiszonki</option>
                        <option value="Okopowe">Okopowe</option>
                    </select>
                </div>
                <div class="pageTitle">
                    <h5> Podaj zawartość składników pokarmowych na 1 kilogram surowca:</h5>
                </div>
                <?php
    
                $sql = "SELECT id, nazwa, kategoria, jednostka FROM składniki";
                $result = $conn->query($sql);
                
                $categories = [];
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $categories[$row["kategoria"]][] = $row;
                    }
                }

                foreach ($categories as $category => $items) {
                    echo "<div class='category' data-bs-toggle='tooltip' data-bs-title='Kliknij aby rozwinąć/zwinąć' id='$category' onclick='showHide(\"$category" . "Ingredients\")'><span class='categoryColor form-control form-control-sm'>$category</span></div>";
                    echo "<div id='$category" . "Ingredients' class='detailsClose detailsColor '>";
                    echo '<div class="input-group inputSection">';
                    echo '<label class="form-control form-control-sm idLabel">' . "ID" . '</label>';
                    echo '<label class="form-control form-control-sm nameWrap">' . "Nazwa składnika" . '</label>';
                    echo '<label class="form-control form-control-sm valueLabel">' . "Zawartość" . '</label>';
                    echo '<label class="form-control form-control-sm unitLabel">' ."Jednostka" . '</label>';
                    echo '</span>';
                echo '</div>';
                    foreach ($items as $item) {
                        echo "<div class='input-group inputSection'>";
                        echo '<label class="form-control form-control-sm idLabel">' . $item["id"] . '</label>';
                        echo '<label class="form-control form-control-sm nameWrap">' . $item["nazwa"] . '</label>';
                        echo '<input type="number" min="0" max="99999" step="0.01" class="form-control form-control-sm valueLabel" name="'.$item["id"].'">';
                        echo '<label class="form-control form-control-sm unitLabel">' . "[". $item["jednostka"] . "]" . '</label>';
                        echo "</div>";
                    }
                    echo "</div>";
                }
                ?>
                <div class="buttonSubmit">
                    <button type="submit" name="addRawMaterial" class="btn btn-primary mb-3">Zapisz</button>
                </div>
            </div>
          </form>
          <p id="text"></p>
    </div>

    <?php

    if(isset($_POST['addRawMaterial'])){
        $category = $_POST['kategoria'];
        $name = $_POST['nazwa'];
        $price = $_POST['cena'];
        $sql2 = "SELECT id, nazwa, jednostka FROM składniki";
        
        $result2 = $conn->query($sql2);
      
        if ($result2->num_rows > 0) {
            $sql3 = "INSERT INTO surowce (kategoria,nazwa,cena,";

            while ($row2 = $result2->fetch_assoc()) {
                $sql3 .= "`" . $row2["nazwa"]. "`, ";
            }

            $sql3 = substr($sql3, 0, -2) . ") VALUES ('$category','$name','$price',";

            mysqli_data_seek($result2, 0);

            while ($row2 = $result2->fetch_assoc()) {
                $id = $row2["id"];
                $amount = $_POST[$id];
                $sql3 .= "'" . $amount . "', ";
            }

            $sql3 = substr($sql3, 0, -2) . ")";

            if ($conn->query($sql3) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql3 . "<br>" . $conn->error;
            }
            echo $sql3;
        } else {
            echo "0 results";
        }
        header("Location: viewRawMaterials.php");
        $conn->close();
    }
?>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    function showHide(id) {
        var details = document.getElementById(id);
        var category = document.getElementById(id.replace("Ingredients", ""));
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
    document.addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
    }
    });
</script>

</body>

</html>						