<?php
include '../templates/header.html';
include '../templates/conn.php';
?>
  <div class="container">
        <div class="pageTitle">
            <h4>Edytuj surowiec:</h4>
        </div>
        <?php
        if(isset($_GET['id'])){
            $id_surowca = $_GET['id'];
        }else
        {
            header("Location: viewRawMaterials.php");
        }
        $sql = "SELECT * FROM surowce WHERE id = $id_surowca";
        $result = $conn->query($sql);

        $contents = [];
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $contents[$row["id"]] = $row;
                echo '<form action="editRawMaterial.php" method="post">';
                echo '<div>';
                echo '<div class="input-group mb-1">';
                echo '<label class="form-control form-control-sm tags nameLabel radiusRemove">Nazwa</label>';
                echo '<input type="text" class="form-control form-control-sm radiusRemove" value="'. $row["nazwa"]. '" maxlength="50" required name="nazwa">';
                echo '<input type="text" value="'.$id_surowca.'" name="id" hidden>';
                echo '</div>';
                echo '<div class="input-group mb-1">';
                echo '<label class="form-control form-control-sm tags nameLabel radiusRemove">Cena</label>';
                echo '<input type="number" min="0" max="99999" step="0.01" class="form-control form-control-sm"  value="'. $row["cena"]. '" name="cena" required>';
                echo '<label class="form-control form-control-sm tags unitLabel">zł/t</label>';
                echo '</div>';
                echo '<select id="kategoria" name="kategoria" class="form-control form-control-sm radiusRemove" required>';
                echo '<option selected value="'. $row["kategoria"]. '"">' . $row["kategoria"]. '</option>';
                if ($row["kategoria"] != "Ziarna i nasiona") {
                    echo '<option value="Ziarna i nasiona">Ziarna i nasiona</option>';
                }
                if ($row["kategoria"] != "Pasze przemysłowe pochodzenia roślinnego") {
                    echo '<option value="Pasze przemysłowe pochodzenia roślinnego">Pasze przemysłowe pochodzenia roślinnego</option>';
                }
                if ($row["kategoria"] != "Pasze przemysłowe pochodzenia zwierzęcego") {
                    echo '<option value="Pasze przemysłowe pochodzenia zwierzęcego">Pasze przemysłowe pochodzenia zwierzęcego</option>';
                }
                if ($row["kategoria"] != "Susze z roślin zielonych") {
                    echo '<option value="Susze z roślin zielonych">Susze z roślin zielonych</option>';
                }
                if ($row["kategoria"] != "Zielonki") {
                    echo '<option value="Zielonki">Zielonki</option>';
                }
                if ($row["kategoria"] != "Kiszonki") {
                    echo '<option value="Kiszonki">Kiszonki</option>';
                }
                if ($row["kategoria"] != "Okopowe") {
                    echo '<option value="Okopowe">Okopowe</option>';
                }
                echo '</select>';
                echo '</div>';
                }
            }
        ?>
                <div class="pageTitle">
                    <h5> Podaj zawartość składników pokarmowych na 1 kilogram surowca:</h5>
                </div>
                <?php
    
                $sql2 = "SELECT id, nazwa, kategoria, jednostka FROM składniki";
                $result2 = $conn->query($sql2);
                
                $categories = [];
                if ($result2->num_rows > 0) {
                    while ($row2 = $result2->fetch_assoc()) {
                        $categories[$row2["kategoria"]][] = $row2;
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
                        echo '<input type="number" min="0" max="99999" step="0.01" value="'.$contents[$id_surowca][$item["nazwa"]].'" class="form-control form-control-sm valueLabel" name="'.$item["id"].'">';
                        echo '<label class="form-control form-control-sm unitLabel">' . "[". $item["jednostka"] . "]" . '</label>';
                        echo "</div>";
                    }
                    echo "</div>";
                }
                ?>
                <div class="buttonSubmit">
                    <button type="submit" name="editRawMaterial" class="btn btn-primary mb-3">Zapisz</button>
                </div>
            </div>
          </form>
          <p id="text"></p>
    </div>

    <?php
    if(isset($_POST['editRawMaterial'])){
        $category = $_POST['kategoria'];
        $name = $_POST['nazwa'];
        $price = $_POST['cena'];
        $id = $_POST['id'];
        $sql2 = "SELECT id, nazwa, jednostka FROM składniki";
        $result2 = $conn->query($sql2);

        if ($result2->num_rows > 0) {
            $sql3 = "UPDATE surowce SET `kategoria` = '$category', `nazwa` = '$name', `cena` = '$price',";
            
            while ($row2 = $result2->fetch_assoc()) {
                $sql3 .= "`" . $row2["nazwa"]. "` = '".$_POST[$row2["id"]]."', ";
            }
            $sql3 = substr($sql3, 0, -2) . " ";
            $sql3 .= " WHERE id = $id";
            if ($conn->query($sql3) === TRUE) {
                echo "Record updated successfully";
            } else {
                echo "Error: " . $sql3 . "<br>" . $conn->error;
            }
        
            
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