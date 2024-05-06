<?php
include '../templates/header.html';
include '../templates/conn.php';
session_start();
?>
    <div class="container">
        <br>
        <div class="pageTitle"> 
            <h4>Pasza:</h4>
        </div>
        <?php
        if(isset($_SESSION["nazwa_paszy"])) {
            $nazwa_paszy = $_SESSION["nazwa_paszy"];
        }else
        {
            header("Location: viewFodders.php");
        }
        $sqlFodder = "SELECT * FROM pasze";
        $resultFodder = $conn->query($sqlFodder);
        if( $resultFodder->num_rows > 0){
            while($rowFodder = $resultFodder->fetch_assoc()){
                if($rowFodder["nazwa_paszy"] == $nazwa_paszy){
                    $id_paszy = $rowFodder["id"];
                    echo "<div class='specification-container'>";
                            echo "<div class='specification'>";
                                echo "<div class='input-group input-group-sm'>";
                                    echo "<label class='form-control tags'>";
                                    echo "Nazwa paszy";
                                    echo "</label>";
                                    echo "<label class='form-control'>";
                                    echo $rowFodder["nazwa_paszy"];
                                    echo "</label>";
                                echo "</div>";
                                    $amount = $rowFodder["ilosc"];
                            echo "</div>";

                            echo "<div class='specification'>";
                                echo "<div class='input-group input-group-sm'>";
                                    echo "<label class='form-control tags'>";
                                    echo "Oznaczenie";
                                    echo "</label>";
                                    echo "<label class='form-control'>";
                                    echo $rowFodder["oznaczenie"];
                                    echo "</label>";
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                }
            }
        }
        ?>
        <div class="pageTitle"> 
            <h4>Na bazie zwierzęcia:</h4>
        </div>
            <?php
            
                if (isset($_GET['id'])) {
                    $idr = $_GET['id'];
                }
                $sql = "SELECT id, nazwa, grupa_zwierzat, grupa_produkcyjna, faza_produkcji FROM zwierzęta";
                $sql2 ="SELECT s.id, s.nazwa, zs.min, zs.max, s.jednostka 
                FROM składniki s 
                JOIN zwierzę_składnik zs ON s.id = zs.id_składnik 
                WHERE zs.id_zwierze = $idr ORDER BY zs.id_składnik ASC";

                $result = $conn->query($sql);
                $result2 = $conn->query($sql2);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        if ($row["id"] == $idr)
                        {
                            echo "<div class='specification-container'>";
                            echo "<div class='specification mb-3'>";
                                echo "<div class='input-group input-group-sm'>";
                                    echo "<label class='form-control tags'>";
                                    echo "Nazwa zwierzęcia";
                                    echo "</label>";
                                    echo "<label class='form-control'>";
                                    echo $row["nazwa"];
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

                            echo "<div class='specification mb-3'>";
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
                } else {
                }
            ?>
                <div class="boxRecipe">
                    <div class='input-group'>
                        <label class='form-control form-control-sm idLabel'> ID </label>
                        <label class='form-control form-control-sm nameWrap'> Składnik </label>                  
                        <label class='form-control form-control-sm labelSize60'> Min </label>                      
                        <label class='form-control form-control-sm labelSize60'> Max </label>                      
                        <label class='form-control form-control-sm labelSize80'> Zawartość</label>                   
                    </div>
                    <?php
                    
                    $result2 = $conn->query($sql2);
                    if ($result2->num_rows > 0) {
                        while ($row2 = $result2->fetch_assoc()) {
                            echo "<div class='input-group' id='row2-" . $row2['id']."'>";
                            echo "<label class='form-control form-control-sm idLabel'>" . $row2["id"] . "</label>";
                            echo "<label class='form-control form-control-sm nameWrap' >" . $row2["nazwa"] . " [" . $row2["jednostka"] . "]" . "</label>";
                            echo "<label class='form-control form-control-sm labelSize60' >" . $row2["min"] . "</label>" ;
                            echo "<label class='form-control form-control-sm labelSize60' >" . $row2["max"] . "</label>" ;
                            echo "<label name='".$row2['nazwa']."' id='".$row2['nazwa']."' class='form-control form-control-sm labelSize80'>" . 0 . "</label>";
                            echo "</div>";
                        }
                    }  
                    ?>
                </div>    
                <div style="clear: both;"></div>
                    <div class='specification-container'>
                        <div class='specification  mb-3'>
                            <div class='input-group input-group-sm'>
                                <label class='form-control tags'>
                                    Koszt
                                </label>
                                <label class='form-control' id="fullCost">
                                    0.00 zł
                                </label>
                            </div>
                        </div>
                        <div class='specification  mb-3'>
                            <div class='input-group input-group-sm'>
                                <label class='form-control tags'>
                                    Ilość
                                </label>
                                <label class='form-control' id="fullAmount">
                                    <?php
                                    echo "0.00 / " . $amount . " kg";
                                    ?>
                                </label>
                                <label id="number" hidden>
                                    <?php
                                    echo $amount;
                                    ?>
                                </label>
                            </div>
                        </div>
                            
                    </div>
        <form action="addFodderN.php" method="post">
            <div id="dane2">
                
                <div class="row box margin-bottom-25">
                    <br>
                        <?php
                            $sql3 = "SELECT * FROM `surowce`";
                            $result3 = $conn->query($sql3);
                    
                            $sql4 = "SELECT `id`, `nazwa`, `kategoria`, `jednostka` FROM `składniki`";
                            $result4 = $conn->query($sql4);
                            
                            $categories = [];
                            if ($result3->num_rows > 0) {
                                while ($row3 = $result3->fetch_assoc()) {
                                    $categories[$row3["kategoria"]][] = $row3;
                                }
                            }
                            
                            $units = [];
                            if ($result4->num_rows > 0) {
                                while ($row4 = $result4->fetch_assoc()) {
                                    $units[$row4["nazwa"]] = $row4["jednostka"];
                                }
                            }
                    
                            foreach ($categories as $category => $items) {
                                echo "<div class='category' id='$category' data-bs-toggle='tooltip' data-bs-title='Kliknij aby rozwinąć/zwinąć' onclick='showHide(\"$category" . "Ingredients\")'><span class='categoryColor form-control form-control-sm'>$category</span></div>";
                                echo "<div id='$category" . "Ingredients' style='display: none;'>";
                                foreach ($items as $item) {
                                    echo "<div class='input-group inputSection' data-bs-toggle='tooltip' data-bs-title='Kliknij aby rozwinąć/zwinąć' id='ingredient" . $item["id"] . "'>";
                                    echo "<label class='form-control form-control-sm idLabel'>" . $item["id"] . "</label>";
                                    echo "<label class='form-control form-control-sm nameWrap nameRawMaterialLabel' onclick='showHide(\"ingredient" . $item["id"] . "Details\")'>" . $item["nazwa"] . "</label>";
                                    echo "<label class='form-control form-control-sm labelSize100'>" . ($item["cena"] != null ? $item["cena"] . ' zł/t' : 'Brak danych') . "</label>";
                                    echo "<button class='btn btn-sm btn-outline-secondary action' type='button' onclick='addRawMaterial(\"" . $item["id"] . "\", this, \"" . $item["nazwa"] . "\", \"" . $item["cena"] . "\", \"" . $amount . "\")'>Dodaj</button>";
                                    echo "</div>";
                                    echo "<div id='ingredient" . $item["id"] . "Details' style='display: none;'>";
                                    foreach ($item as $key => $value) {
                                        if (!in_array($key, ['id', 'kategoria', 'nazwa', 'cena'])) {
                                            if($value > 0)
                                            {
                                                echo "<div class='input-group inputSection2'>";
                                                echo "<label class='form-control form-control-sm nameWrap'>" . $key ."</label>";
                                                echo "<label class='form-control form-control-sm text-align-end'>" . $value ."</label>";
                                                echo "<label class='form-control form-control-sm labelSize60'>". $units[$key] . "</label>";
                                                echo "</div>";
                                            }
                                        }
                                    }
                                    echo "</div>";
                                }
                                echo "</div>";
                            }
                        ?>
                    
                </div>
                <div class="middle">
                </div>
                <div class="boxr" id="right2">
                    <div class='input-group'>
                        <label class='form-control form-control-sm idLabel'> ID </label>
                        <label class='form-control form-control-sm nameWrap'> Surowiec </label>                            
                        <label class='form-control form-control-sm labelSize60'> Min [%] </label>                      
                        <label class='form-control form-control-sm labelSize60'> Max [%] </label>                      
                        <label class='form-control form-control-sm labelSize60'> Udział [%] </label>                      
                        <label class='form-control form-control-sm labelSize60'> Ilość [kg] </label>                      
                        <button class='btn btn-sm buttonDesc' disabled> Usuń </button>                      
                    </div>
                </div>
                <div style="clear: both;"></div>
                <div class="buttonSubmit2">
                    <button type="submit" name="addFodderN" class="btn btn-primary mb-3" id="buttonSave2">Zapisz</button>
                </div>
            </div>
            
        </form>
        
    </div>
    
    
<?php
include 'conn.php';

if(isset($_POST['addFodderN']))
{   
    $materials = $_POST['material'];
    $mins = $_POST['minS'];
    $maxs = $_POST['maxS'];
    $contents = $_POST['content'];
    echo '<pre>';
    print_r($materials);
    print_r($mins);
    print_r($maxs);
    echo '</pre>';


    $deletedItems = $POST['deletedItem'];
    echo '<pre>';
    print_r($deletedItems);
    echo '</pre>';
    $deletedItems = $_POST['deletedItem'];
    foreach($deletedItems as $id => $deletedItem) {
        if ($deletedItem === 'true') {
            $sqlDelete = "DELETE FROM pasza_surowiec WHERE id_pasza = $id_paszy AND id_surowiec = $id";
            if ($conn->query($sqlDelete) === TRUE) {
                echo "Surowiec o ID $id został usunięty.";
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        }
    }
    foreach($materials as $id => $material) {
        $min = $mins[$id];
        $max = $maxs[$id];
        $content = $contents[$id];

        $sqlCheck = "SELECT * FROM pasza_surowiec WHERE id_pasza = $id_paszy AND id_surowiec = $id";
        $resultCheck = $conn->query($sqlCheck);
            if ($resultCheck->num_rows == 0) {
                $sql = "INSERT INTO pasza_surowiec (id_pasza, id_surowiec, min, max, ilosc) VALUES ('" . $id_paszy. "', '$id', '$min', '$max', '$content')";

                if ($conn->query($sql) === TRUE) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }else
        {
            $sqlUpdate = "UPDATE pasza_surowiec SET min = $min, max = $max, ilosc = $content WHERE id_pasza = $id_paszy AND id_surowiec = $id";
            if ($conn->query($sqlUpdate) === TRUE) {
                echo "Surowiec o ID $id został zaktualizowany.";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        }
    }
    header("Location: viewFodders.php");
    $conn->close();
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    var rawMaterialCost = {};
    var rawMaterialAmount = {};

    var totals = {};
    var originalValues = {};
    var amount = document.getElementById("number").innerHTML;
    
    function deleteRawMaterial(id){
        document.getElementById('rowS-' + id).remove();
        document.getElementById('button' + id).disabled = false;

        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'deletedItem[' + id + ']';
        input.value = 'true';
        document.getElementById('right2').appendChild(input);
    }
    function update(id, price){
        var amountFull = 0;
        var costFull = 0;
        var quantity = document.getElementById('quantity['+id+']');
        var content = document.getElementById('content['+id+']');
                if(content == null || quantity == null)
        {
            var content = 0;
            var quantity = 0;
        }else{
            quantity.value = parseFloat((amount*content.value)/100);
        }
        var ingredientDetails = document.getElementById('ingredient' + id + 'Details').getElementsByClassName('inputSection2');
            
            var ingredients = {};
            for (var i = 0; i < ingredientDetails.length; i++) {
                var ingredientName = ingredientDetails[i].getElementsByClassName('nameWrap')[0].textContent;
                var ingredientValue = ingredientDetails[i].getElementsByClassName('form-control form-control-sm')[1].textContent;
                var ingredientAdded = document.getElementById(ingredientName);
              
                if (ingredientAdded != null) {
                    var key = ingredientName + '_' + id; 
                    if (typeof totals[ingredientName] === 'undefined') {
                        totals[ingredientName] = 0;
                    }
                    if (typeof originalValues[key] !== 'undefined') {
                        totals[ingredientName] -= originalValues[key];
                    }
                    if(quantity == 0)
                    {
                        originalValues[key] = 0;
                    }else
                    {
                        originalValues[key] = parseFloat(((ingredientValue / amount) * quantity.value)); 
                    }
                    totals[ingredientName] += originalValues[key];

                    ingredientAdded.innerHTML = totals[ingredientName].toFixed(2);
                    ingredients[ingredientName] = ingredientValue;
                }
                }
                if(content == 0)
                {
                    rawMaterialCost[id] = 0;
                    rawMaterialAmount[id] = 0;
                }else
                {
                    rawMaterialCost[id] = price/1000 * parseFloat((amount*content.value)/100);
                    rawMaterialAmount[id] = parseFloat(quantity.value);
                }
            
            var fullAmount = document.getElementById("fullAmount");
            var fullCost = document.getElementById("fullCost");

            for (var key in rawMaterialCost) {
                if (rawMaterialCost.hasOwnProperty(key)) {
                    costFull += rawMaterialCost[key];
                }
            }
            fullCost.innerHTML = costFull.toFixed(2) + ' zł';

            for (var key in rawMaterialAmount) {
                if (rawMaterialAmount.hasOwnProperty(key)) {
                    amountFull += rawMaterialAmount[key];
                }
            }
            fullAmount.innerHTML = amountFull.toFixed(2) + ' / ' + amount + ' kg';
    };
    function addRawMaterial(id, button, name, price, amount) {
        
        var rowS = document.createElement('div');
        rowS.className = 'input-group';
        rowS.id = 'rowS-' + id;

        var idFieldS = document.createElement('label');
        idFieldS.textContent  = id;
        idFieldS.className = 'form-control form-control-sm idLabel';

        rowS.appendChild(idFieldS);

        var materialField = document.createElement('input');
        materialField.type = 'text';
        materialField.name = 'material[' + id + ']';
        materialField.value = name;
        materialField.innerHTML = name;
        materialField.className = 'form-control form-control-sm nameWrap';
        materialField.readOnly = true;

        rowS.appendChild(materialField);

        var priceFieldS = document.createElement('input');
        priceFieldS.type = 'text';
        priceFieldS.readOnly = true;
        priceFieldS.name = 'price[' + id + ']';
        priceFieldS.className = 'form-control form-control-sm labelSize70';
        priceFieldS.value = (price !== null && price !== '') ? price : 'Brak danych';
        priceFieldS.hidden = true;
        rowS.appendChild(priceFieldS);

        var minFieldS = document.createElement('input');
        minFieldS.type = 'number';
        minFieldS.name = 'minS[' + id + ']';
        minFieldS.className = 'form-control form-control-sm labelSize60';
        minFieldS.onchange = function() {
            if (parseFloat(minFieldS.value) > 100 || parseFloat(minFieldS.value) < 0) {
                alert('Wartość min musi być między 0 a 100');
                minFieldS.value = '';
            }
        };
        rowS.appendChild(minFieldS);

        var maxFieldS = document.createElement('input');
        maxFieldS.type = 'number';
        maxFieldS.name = 'maxS[' + id + ']';
        maxFieldS.className = 'form-control form-control-sm labelSize60'; 
        maxFieldS.onchange = function() {
            if (parseFloat(maxFieldS.value) > 100 || parseFloat(maxFieldS.value) < 0) {
                alert('Wartość max musi być między 0 a 100');
                maxFieldS.value = '';
            }
        };
        rowS.appendChild(maxFieldS);

        var quantityFieldS = document.createElement('input');
        quantityFieldS.type = 'text';
        quantityFieldS.name = 'quantity[' + id + ']';
        quantityFieldS.className = 'form-control form-control-sm labelSize60'; 
        quantityFieldS.readOnly = true;
        

        
        var contentFieldS = document.createElement('input');
        contentFieldS.type = 'number';
        contentFieldS.name = 'content[' + id + ']';
        contentFieldS.className = 'form-control form-control-sm labelSize60'; 
        
        contentFieldS.onchange =function(){
            if (parseFloat(contentFieldS.value) > 100 || parseFloat(contentFieldS.value) < 0) {
                alert('Wartość udziału musi być między 0 a 100');
                contentFieldS.value = '';
            }
            var amountFull = 0;
            var costFull = 0;
            quantityFieldS.value = parseFloat((amount*contentFieldS.value)/100);
            var ingredientDetails = document.getElementById('ingredient' + id + 'Details').getElementsByClassName('inputSection2');
            
            var ingredients = {};
            for (var i = 0; i < ingredientDetails.length; i++) {
                var ingredientName = ingredientDetails[i].getElementsByClassName('nameWrap')[0].textContent;
                var ingredientValue = ingredientDetails[i].getElementsByClassName('form-control form-control-sm')[1].textContent;
                var ingredientAdded = document.getElementById(ingredientName);

                if (ingredientAdded != null) {
                    var key = ingredientName + '_' + id; 

                    if (typeof totals[ingredientName] === 'undefined') {
                        totals[ingredientName] = 0;
                    }

                    if (typeof originalValues[key] !== 'undefined') {
                        totals[ingredientName] -= originalValues[key];
                    }

                    originalValues[key] = parseFloat(((ingredientValue / amount) * quantityFieldS.value)); 
                    totals[ingredientName] += originalValues[key];

                    ingredientAdded.innerHTML = totals[ingredientName].toFixed(2);
                    ingredients[ingredientName] = ingredientValue;
                }
                }
            
            rawMaterialCost[id] = price/1000 * parseFloat((amount*contentFieldS.value)/100);
            rawMaterialAmount[id] = parseFloat(quantityFieldS.value);
            
            var fullAmount = document.getElementById("fullAmount");
            var fullCost = document.getElementById("fullCost");

            for (var key in rawMaterialCost) {
                if (rawMaterialCost.hasOwnProperty(key)) {
                    costFull += rawMaterialCost[key];
                }
            }
            fullCost.innerHTML = costFull.toFixed(2) + ' zł';

            for (var key in rawMaterialAmount) {
                if (rawMaterialAmount.hasOwnProperty(key)) {
                    amountFull += rawMaterialAmount[key];
                }
            }
            fullAmount.innerHTML = amountFull.toFixed(2) + ' / ' + amount + ' kg';
        }
        rowS.appendChild(contentFieldS);

        

        rowS.appendChild(quantityFieldS);

        var removeButton = document.createElement('button');
        removeButton.innerHTML = 'Usuń';
        removeButton.type='button'
        removeButton.className='btn btn-sm btn-outline-secondary action' 
        removeButton.onclick = function() {
            document.getElementById('rowS-' + id).remove();
            update(id,price);
            button.disabled = false;
            deleteRawMaterial(id);
        };
        removeButton.name = 'deletedItemS[' + id + ']';
        rowS.appendChild(removeButton);
        document.getElementById('right2').appendChild(rowS);
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