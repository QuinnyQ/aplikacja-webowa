<?php
include '../templates/header.html';
include '../templates/conn.php';
session_start();
if (isset($_SESSION['nazwa'])) {
}else
{
    header("Location: animal/viewAnimals.php");
}
?>
    <div class="container">
        <div class="pageTitle"> 
            <h4>Zwierzę:</h4>
        </div>
            <?php
                echo "<div class='specification-container'>";
                    echo "<div class='specification'>";
                        echo "<div class='input-group input-group-sm'>";
                            echo "<label class='form-control tags'>";
                            echo "Nazwa";
                            echo "</label>";
                            echo "<label class='form-control'>";
                            echo $_SESSION["nazwa"];
                            echo "</label>";
                        echo "</div>";
                        echo "<div class='input-group input-group-sm'>";
                            echo "<label class='form-control tags'>";
                            echo "Grupa zwierząt";
                            echo "</label>";
                            echo "<label class='form-control'>";
                            echo $_SESSION["grupaz"];
                            echo "</label>";
                        echo "</div>";
                    echo "</div>";
                    echo "<div class='specification'>";
                        echo "<div class='input-group input-group-sm'>";
                            echo "<label class='form-control tags'>";
                            echo "Grupa produkcyjna";
                            echo "</label>";
                            echo "<label class='form-control'>";
                            echo $_SESSION["grupap"];
                            echo "</label>";
                        echo "</div>";
                        echo "<div class='input-group input-group-sm'>";
                            echo "<label class='form-control tags'>";
                            echo "Faza produkcyjna";
                            echo "</label>";
                            echo "<label class='form-control'>";
                            echo $_SESSION["fazap"];
                            echo "</label>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
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
        <form action="addAnimalN.php" method="post">
            <div id="dane">
                <div class="row box">
                    <br>
                        <?php
                            $sql1 = "SELECT id, nazwa FROM zwierzęta";
                            $result1 = $conn->query($sql1);
                            if ($result1->num_rows > 0) {
                                while ($row1 = $result1->fetch_assoc()) {
                                    if ($row1["nazwa"] == $_SESSION["nazwa"])
                                    {
                                        $id_animal = $row1["id"];
                                    }
                                }
                            } else {
                            }
                            $sql = "SELECT id, nazwa, kategoria, jednostka FROM składniki";
                            $result = $conn->query($sql);
                            $categories = [];
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $categories[$row["kategoria"]][] = $row;
                                }
                            }
                            foreach ($categories as $category => $items) {
                                echo "<div class='category' id='$category' onclick='showHide(\"$category" . "Ingredients\")'><span class='categoryColor form-control form-control-sm'>$category</span></div>";
                                echo "<div id='$category" . "Ingredients' style='display: none;'>";
                                foreach ($items as $item) {
                                    echo "<div class='input-group inputSection'>";
                                    echo "<label class='form-control form-control-sm idLabel'>" . $item["id"] . "</label>";
                                    echo "<label class='form-control form-control-sm nameWrap'>" . $item["nazwa"] . "</label>";
                                    echo "<label class='form-control form-control-sm amountLabel'>" . $item["jednostka"] . "</label>";
                                    echo "<button class='btn btn-sm btn-outline-secondary' type='button' onclick='addIngredient(\"" . $item["id"] . "\", this, \"" . $item["nazwa"] . "\", \"" . $item["jednostka"] . "\")'>Dodaj</button>";
                                    echo "</div>";
                                }
                                echo "</div>";
                            }
                            ?> 
                </div>
                <div class="middle">
                </div>
                <div class="boxr" id="right">
                    <div class='input-group'>
                        <label class='form-control form-control-sm idLabel'> ID </label>
                        <label class='form-control form-control-sm nameWrap'> Nazwa składnika </label>                        
                        <label class='form-control form-control-sm amountLabel'> Min </label>                      
                        <label class='form-control form-control-sm amountLabel'> Max </label>                            
                        <button class='btn btn-sm buttonDesc' disabled> Usuń </button>                      
                    </div>
                </div>
                <div class="buttonSubmit">
                    <button type="submit" name="addAnimalN" class="btn btn-primary mb-3" id="buttonSave">Zapisz</button>
                </div>
            </div>
        </form>
    </div>
<?php
if(isset($_POST['addAnimalN']))
{   
    echo 'Formularz został przesłany.<br>';
    $ingredients = $_POST['ingredient'];
    $mins = $_POST['min'];
    $maxs = $_POST['max'];

    foreach($ingredients as $id => $ingredient) {
        $min = $mins[$id];
        $max = $maxs[$id];
        $sql = "INSERT INTO zwierzę_składnik (id_zwierze, id_składnik, min, max) VALUES ('$id_animal', '$id', '$min', '$max')";

        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    $conn->close();
    header("Location: viewAnimals.php");
    session_destroy();
}
?>
<script>
    function addIngredient(id, button, name, unit) {
        var row = document.createElement('div');
        row.className = 'input-group';
        row.id = 'row-' + id;

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
        ingredientNameField.innerHTML = name + " [" + unit + " ]";
        ingredientNameField.className = 'form-control form-control-sm nameWrap';

        row.appendChild(ingredientNameField);

        var minField = document.createElement('input');
        minField.type = 'number';
        minField.name = 'min[' + id + ']';
        minField.min="0";
        minField.max="99999";
        minField.step="0.01";
        minField.className = 'form-control form-control-sm amountLabel';
       
        row.appendChild(minField);

        var maxField = document.createElement('input');
        maxField.type = 'number';
        maxField.name = 'max[' + id + ']';
        maxField.min="0";
        maxField.max="99999";
        maxField.step="0.01";
        maxField.className = 'form-control form-control-sm amountLabel'; 
        
        row.appendChild(maxField);

        var removeButton = document.createElement('button');
        removeButton.innerHTML = 'Usuń';
        removeButton.type='button'
        removeButton.className='btn btn-sm btn-outline-secondary' 
        removeButton.onclick = function() {
            document.getElementById('row-' + id).remove();

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