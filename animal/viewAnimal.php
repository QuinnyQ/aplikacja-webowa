<?php
include '../templates/header.html';
include '../templates/conn.php';
session_start();
?>
    <div class="container">
        <div class="pageTitle"> 
            <h4>Zwierzę:</h4>
        </div>
            <?php
                if (isset($_GET['id'])) {
                    $_SESSION['id_zwierze'] = $_GET['id'];
                }else
                {
                    header("Location: animal/viewAnimals.php");
                }
                $sql = "SELECT id, nazwa, grupa_zwierzat, grupa_produkcyjna, faza_produkcji FROM zwierzęta";
                $sql2 ="SELECT s.id, s.nazwa, s.jednostka, zs.min, zs.max 
                FROM składniki s 
                JOIN zwierzę_składnik zs ON s.id = zs.id_składnik 
                WHERE zs.id_zwierze = \"" . $_SESSION['id_zwierze'] . "\" ORDER BY zs.id_składnik ASC";

                $sqlAddedIngredients = "SELECT id_składnik FROM zwierzę_składnik WHERE id_zwierze = \"" . $_SESSION['id_zwierze'] . "\"";
                $resultAddedIngredients = $conn->query($sqlAddedIngredients);

                $addedIngredients = [];
                if ($resultAddedIngredients->num_rows > 0) {
                    while ($row = $resultAddedIngredients->fetch_assoc()) {
                        $addedIngredients[] = $row['id_składnik'];
                    }
                }
                $result = $conn->query($sql);
                $result2 = $conn->query($sql2);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        if($row["id"]== $_SESSION['id_zwierze'])
                        {
                            echo "<div class='specification-container'>";
                                echo "<div class='specification'>";
                                    echo "<div class='input-group input-group-sm'>";
                                        echo "<label class='form-control tags'>";
                                        echo "Nazwa";
                                        echo "</label>";
                                        echo "<label class='form-control'>";
                                        echo  $row["nazwa"];
                                        $nazwa_zwierzęcia = $row["nazwa"];
                                        echo "</label>";
                                    echo "</div>";

                                    echo "<div class='input-group input-group-sm'>";
                                        echo "<label class='form-control tags'>";
                                        echo "Grupa zwierząt";
                                        echo "</label>";
                                        echo "<label class='form-control'>";
                                        echo $row["grupa_zwierzat"];
                                        echo "</label>";
                                    echo "</div>";
                                echo "</div>";

                                echo "<div class='specification'>";
                                    echo "<div class='input-group input-group-sm'>";
                                        echo "<label class='form-control tags'>";
                                        echo "Grupa produkcyjna";
                                        echo "</label>";
                                        echo "<label class='form-control'>";
                                        echo $row["grupa_produkcyjna"];
                                        echo "</label>";
                                    echo "</div>";
                                    echo "<div class='input-group input-group-sm'>";
                                        echo "<label class='form-control tags'>";
                                        echo "Faza produkcyjna";
                                        echo "</label>";
                                        echo "<label class='form-control'>";
                                        echo $row["faza_produkcji"];
                                        echo "</label>";
                                    echo "</div>";
                                echo "</div>";
                            echo "</div>";
                        }
                    }
                }
            ?>
            <h4 class="pageTitle">Dodaj wymagane składniki:</h4>
            <div>
                <div class="box2">
                    Wszystkie składniki:
                </div>
                <div class="middle2">
                </div>
                <div class="box2r">
                    Wybrane składniki:
                </div>
            </div>
        <form action="viewAnimal.php" method="post">
            <div id="dane">
                <div class="smallTitle">
                    Wszystkie składniki:
                </div>
                <div class="row box" >
                    <br>
                        <?php
                            $sql3 = "SELECT id, nazwa, kategoria, jednostka FROM składniki";
                            $result3 = $conn->query($sql3);

                            $categories = [];
                            if ($result3->num_rows > 0) {
                                while ($row = $result3->fetch_assoc()) {
                                    $categories[$row["kategoria"]][] = $row;
                                }
                            }
                            foreach ($categories as $category => $items) {
                                echo "<div class='category' id='$category' data-bs-toggle='tooltip' data-bs-title='Kliknij aby rozwinąć/zwinąć' onclick='showHide(\"$category" . "Ingredients\")'><span class='categoryColor form-control form-control-sm'>$category</span></div>";
                                echo "<div id='$category" . "Ingredients' style='display: none;'>";
                                foreach ($items as $item) {
                                    $isAdded = in_array($item['id'], $addedIngredients);
                                    $disabled = $isAdded ? 'disabled' : '';
                                    echo "<div class='input-group inputSection'>";
                                    echo "<label class='form-control form-control-sm idLabel'>" . $item["id"] . "</label>";
                                    echo "<label class='form-control form-control-sm nameWrap'>" . $item["nazwa"] . "</label>";
                                    echo "<label class='form-control form-control-sm amountLabel'>" . $item["jednostka"] . "</label>";
                                    echo "<button class='btn btn-sm btn-outline-secondary' id='button-" . $item['id']."' type='button' $disabled onclick='addIngredient(\"" . $item["id"] . "\", this, \"" . $item["nazwa"] . "\", \"" . $item["jednostka"] . "\")'>Dodaj</button>";
                                    echo "</div>";
                                }
                                echo "</div>";
                            }
                            ?> 
                </div>
                <div class="middle">
                </div>
                <div class="smallTitle">
                    Wybrane składniki:
                </div>
                <div class="boxr" id="right">
                    <div class='input-group'>
                        <label class='form-control form-control-sm idLabel'> ID </label>
                        <label class='form-control form-control-sm nameWrap'> Składnik </label>                        
                        <label class='form-control form-control-sm labelSize85'> Min</label>                    
                        <label class='form-control form-control-sm labelSize85'> Max</label>                           
                        <button class='btn btn-sm buttonDesc' disabled> Usuń </button>                      
                    </div>
                    <?php
                        if ($result2->num_rows > 0) {
                            while ($row2 = $result2->fetch_assoc()) {
                                echo "<div class='input-group' id='row2-" . $row2['id']."'>";
                                echo "<label class='form-control form-control-sm idLabel'>" . $row2["id"] . "</label>";
                                echo "<input type='text' name='ingredient[" . $row2['id'] . "]' class='form-control form-control-sm nameWrap' value='" . $row2["nazwa"] . "' hidden>";
                                echo "<label class='form-control form-control-sm nameWrap'>" . $row2["nazwa"] . " [" . $row2["jednostka"] . "]" . "</label>";
                                echo "<input type='number' min='0' max='99999' step='0.01' name='min[" . $row2['id'] . "]' class='form-control form-control-sm labelSize85' value='" . $row2["min"] . "'>";
                                echo "<input type='number' min='0' max='99999' step='0.01'  name='max[" . $row2['id'] . "]' class='form-control form-control-sm labelSize85' value='" . $row2["max"] . "'>";
                                echo "<button class='btn btn-sm btn-outline-secondary' name='deletedItem[" . $row2['id'] . "]' onclick='deleteIngredient(" . $row2['id'] . ")'>Usuń</button>";
                                echo "</div>";
                            }
                        }               
                    ?>
                </div>
                <div class="buttonSubmit">
                    <button type="submit" name="viewAnimal" class="btn btn-primary mb-3" id="buttonSave">Zapisz</button>
                </div>
            </div>
        </form>
    </div>
<?php
include 'conn.php';
if(isset($_POST['viewAnimal']))
{   
    $ingredients = $_POST['ingredient'];
    $mins = $_POST['min'];
    $maxs = $_POST['max'];
    echo '<pre>';
    print_r($ingredients);
    print_r($mins);
    print_r($maxs);
    echo '</pre>';

    $deletedItems = $_POST['deletedItem'];
    echo '<pre>';
    print_r($deletedItems);
    echo '</pre>';
    foreach($deletedItems as $id => $deletedItem) {
        if ($deletedItem === 'true') {
            $sqlDelete = "DELETE FROM zwierzę_składnik WHERE id_zwierze = $_SESSION[id_zwierze] AND id_składnik = $id";
            if ($conn->query($sqlDelete) === TRUE) {
                echo "Składnik o ID $id został usunięty.";
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        }
    }
    foreach($ingredients as $id => $ingredient) {
        $min = $mins[$id];
        $max = $maxs[$id];

        $sqlCheck = "SELECT * FROM zwierzę_składnik WHERE id_zwierze = $_SESSION[id_zwierze] AND id_składnik = $id";
        $resultCheck = $conn->query($sqlCheck);
            if ($resultCheck->num_rows == 0) {
                $sql = "INSERT INTO zwierzę_składnik (id_zwierze, id_składnik, min, max) VALUES ('" . $_SESSION['id_zwierze'] . "', '$id', '$min', '$max')";

                if ($conn->query($sql) === TRUE) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }else
        {
            $sqlUpdate = "UPDATE zwierzę_składnik SET min = $min, max = $max WHERE id_zwierze = $_SESSION[id_zwierze] AND id_składnik = $id";
            if ($conn->query($sqlUpdate) === TRUE) {
                echo "Składnik o ID $id został zaktualizowany.";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        }
    }

    $conn->close();
    header("Location: viewAnimals.php");
    session_destroy();
}
?>

<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    function deleteIngredient(id){
        document.getElementById('row2-' + id).remove();
        document.getElementById('button-' + id).disabled = false;

        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'deletedItem[' + id + ']';
        input.value = 'true';
        document.getElementById('right').appendChild(input);
    }
    function addIngredient(id, button, name, unit) {
        var row = document.createElement('div');
        row.className = 'input-group';
        row.id = 'row2-' + id;

        var idField = document.createElement('label');
        idField.textContent  = id;
        idField.className = 'form-control form-control-sm idLabel';

        row.appendChild(idField);

        var ingredientField = document.createElement('input');
        ingredientField.type = 'text';
        ingredientField.name = 'ingredient[' + id + ']';
        ingredientField.value = name;
        ingredientField.innerHTML = name;
        ingredientField.className = 'form-control form-control-sm nameWrap';
        ingredientField.readOnly = true;
        ingredientField.hidden = true;

        row.appendChild(ingredientField);

        var ingredientNameField = document.createElement('label');
        ingredientNameField.innerHTML = name + " [" + unit + "]";
        ingredientNameField.className = 'form-control form-control-sm nameWrap';

        row.appendChild(ingredientNameField);

        var minField = document.createElement('input');
        minField.type = 'number';
        minField.name = 'min[' + id + ']';
        minField.min="0";
        minField.max="99999";
        minField.step="0.01";
        minField.className = 'form-control form-control-sm labelSize85';
        row.appendChild(minField);

        var maxField = document.createElement('input');
        maxField.type = 'number';
        maxField.name = 'max[' + id + ']';
        maxField.min="0";
        maxField.max="99999";
        maxField.step="0.01";
        maxField.className = 'form-control form-control-sm labelSize85'; 
        row.appendChild(maxField);

        var removeButton = document.createElement('button');
        removeButton.innerHTML = 'Usuń';
        removeButton.type='button'
        removeButton.className='btn btn-sm btn-outline-secondary' 
        removeButton.name = 'deletedItem[' + id + ']';
        removeButton.onclick = function() {
            deleteIngredient(id);
            button.disabled = false;
        };
        row.appendChild(removeButton);
        document.getElementById('right').appendChild(row);
        button.disabled = true;
    }
    function showHide(id) {
        var x = document.getElementById(id);
        var category = document.getElementById(id.replace("Ingredients", ""));
        if (x.style.display === "none") {
            x.style.display = "block";
            category.classList.add("open");
        } else {
            x.style.display = "none";
            category.classList.remove("open");
        }
        }
</script>



</body>

</html>