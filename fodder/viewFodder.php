<?php
include '../templates/header.html';
include '../templates/conn.php';
session_start();
?>
<body>
<div class="container">
        <br>
        <div class="pageTitle"> 
            <h4>Pasza:</h4>
        </div>
        <?php
        if (isset($_GET['id'])) {
            $_SESSION['id_pasza'] = $_GET['id'];
            $idp = $_GET['id'];
        }else
        {
            header("Location: viewFodders.php");
        }
        $sqlFodder = "SELECT * FROM pasze";
        $resultFodder = $conn->query($sqlFodder);
        if( $resultFodder->num_rows > 0){
            while($rowFodder = $resultFodder->fetch_assoc()){
                if($rowFodder["id"] == $_SESSION['id_pasza']){
                    echo "<div class='specification-container'>";
                            echo "<div class='specification'>";
                                echo "<div class='input-group input-group-sm'>";
                                    echo "<label class='form-control tags'>";
                                    echo "Nazwa paszy";
                                    echo "</label>";
                                    echo "<label class='form-control' id='fodderName'>";
                                    echo $rowFodder["nazwa_paszy"];
                                    $fodderName = $rowFodder["nazwa_paszy"];
                                    echo "</label>";
                                echo "</div>";
                                    $amount = $rowFodder["ilosc"];
                            echo "</div>";

                            echo "<div class='specification'>";
                                echo "<div class='input-group input-group-sm'>";
                                    echo "<label class='form-control tags'>";
                                    echo "Oznaczenie";
                                    echo "</label>";
                                    echo "<label class='form-control' id='mark'>";
                                    echo $rowFodder["oznaczenie"];
                                    $fodderMark = $rowFodder["oznaczenie"];
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
                $sql = "SELECT p.id, p.id_zwierze, p.nazwa_paszy, p.oznaczenie, p.ilosc, z.nazwa, z.grupa_zwierzat, z.grupa_produkcyjna, z.faza_produkcji 
                FROM pasze p
                JOIN zwierzęta z ON p.id_zwierze = z.id AND p.id = \"" . $_SESSION['id_pasza'] . "\" ";
         
                $sql2 ="SELECT s.id, s.nazwa, zs.min, zs.max, s.jednostka 
                FROM składniki s 
                JOIN zwierzę_składnik zs ON s.id = zs.id_składnik 
                JOIN pasze p ON zs.id_zwierze = p.id_zwierze
                WHERE p.id = " . $_SESSION['id_pasza'] . "
                ORDER BY zs.id_składnik ASC";

                $sqlRaw ="SELECT ps.id_pasza, ps.min, ps.max, ps.ilosc, s.id, s.nazwa, s.cena
                FROM pasza_surowiec ps
                JOIN surowce s ON ps.id_surowiec = s.id AND ps.id_pasza = \"" . $_SESSION['id_pasza'] . "\"";

                $sqlAddedMaterials = "SELECT id_surowiec FROM pasza_surowiec WHERE id_pasza = \"" . $_SESSION['id_pasza'] . "\"";
                $resultAddedMaterials = $conn->query($sqlAddedMaterials);

                $addedMaterials = [];
                if ($resultAddedMaterials->num_rows > 0) {
                    while ($row = $resultAddedMaterials->fetch_assoc()) {
                        $addedMaterials[] = $row['id_surowiec'];
                    }
                }
                
                $result = $conn->query($sql);
                $result2 = $conn->query($sql2);
                $resultRaw = $conn->query($sqlRaw);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        if ($row["id"] == $_SESSION['id_pasza'])
                        {
                            echo "<div class='specification-container'>";
                            echo "<div class='specification mb-3'>";
                                echo "<div class='input-group input-group-sm'>";
                                    echo "<label class='form-control tags'>";
                                    echo "Nazwa zwierzęcia";
                                    echo "</label>";
                                    echo "<label class='form-control' id='animalName'>";
                                    echo $row["nazwa"];
                                    $animalName = $row["nazwa"];
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
                                    echo "Faza produkcji";
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
                            echo "<label class='form-control form-control-sm idLabel drukId'>" . $row2["id"] . "</label>";
                            echo "<label class='form-control form-control-sm nameWrap' >" . $row2["nazwa"] . " [" . $row2["jednostka"] . "]" . "</label>";
                            echo "<label class='form-control form-control-sm nameWrap drukNazwa' hidden>" . $row2["nazwa"] . "</label>";
                            echo "<label class='form-control form-control-sm nameWrap drukJednostka' hidden>" . $row2["jednostka"] . "</label>";
                            echo "<label class='form-control form-control-sm labelSize60 drukMin' >" . $row2["min"] . "</label>" ;
                            echo "<label class='form-control form-control-sm labelSize60 drukMax' >" . $row2["max"] . "</label>" ;
                            echo "<label name='".$row2['nazwa']."' id='".$row2['nazwa']."' class='form-control form-control-sm labelSize80 drukZawartosc'>" . 0 . "</label>";
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
        <form action="viewFodder.php" method="post">
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
                                    $isAdded = in_array($item['id'], $addedMaterials);
                                    $disabled = $isAdded ? 'disabled' : '';
                                    echo "<div class='input-group inputSection' data-bs-toggle='tooltip' data-bs-title='Kliknij aby rozwinąć/zwinąć'  id='ingredient" . $item["id"] . "'>";
                                    echo "<label class='form-control form-control-sm idLabel'>" . $item["id"] . "</label>";
                                    echo "<label class='form-control form-control-sm nameWrap nameRawMaterialLabel' onclick='showHide(\"ingredient" . $item["id"] . "Details\")'>" . $item["nazwa"] . "</label>";
                                    echo "<label class='form-control form-control-sm labelSize100'>" . ($item["cena"] != null ? $item["cena"] . ' zł/t' : 'Brak danych') . "</label>";
                                    echo "<button class='btn btn-sm btn-outline-secondary action' type='button' id='button" . $item["id"] . "' $disabled onclick='addRawMaterial(\"" . $item["id"] . "\", this, \"" . $item["nazwa"] . "\", \"" . $item["cena"] . "\", \"" . $amount . "\")'>Dodaj</button>";
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
                    <?php
                        $surowce = $resultRaw->fetch_all(MYSQLI_ASSOC);
                        echo "<script>var surowce = " . json_encode($surowce) . ";</script>";    
                    ?>
                </div>
                <div style="clear: both;"></div>
                <div class="buttonSubmit">
                    <button type="submit" name="viewFodder" class="btn btn-primary mb-3">Zapisz</button>
                    <a href="#" onclick="printAllElements()" class="btn btn-primary mb-3">Wydrukuj etykietę</a>
                </div>
            </div>
        </form>
    </div>
    <div id="feedLabel">
    </div>
    <?php
include 'conn.php';
if(isset($_POST['viewFodder']) || isset($_POST['wydruk']))
{   
    var_dump($_POST);
    $materials = $_POST['material'];
    $mins = $_POST['minS'];
    $maxs = $_POST['maxS'];
    $contents = $_POST['content'];
    echo '<pre>';
    print_r($materials);
    print_r($mins);
    print_r($maxs);
    echo '</pre>';

    $deletedItems = $_POST['deletedItem'];
    
    foreach($deletedItems as $id => $deletedItem) {
        if ($deletedItem === 'true') {
            $sqlDelete = "DELETE FROM pasza_surowiec WHERE id_pasza = " . $_SESSION['id_pasza'] . " AND id_surowiec = $id";
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

        $sqlCheck = "SELECT * FROM pasza_surowiec WHERE id_pasza = " . $_SESSION['id_pasza'] . " AND id_surowiec = $id";
        echo $sqlCheck;
        $resultCheck = $conn->query($sqlCheck);
            if ($resultCheck->num_rows == 0) {
                $sqlInsert = "INSERT INTO pasza_surowiec (id_pasza, id_surowiec, min, max, ilosc) VALUES ('" . $_SESSION['id_pasza'] . "', '$id', '$min', '$max', '$content')";
                echo $sqlInsert;
                if ($conn->query($sqlInsert) === TRUE) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $sqlInsert . "<br>" . $conn->error;
            }
        }else
        {
            $sqlUpdate = "UPDATE pasza_surowiec SET min = $min, max = $max, ilosc = $content WHERE id_pasza = " . $_SESSION['id_pasza'] . " AND id_surowiec = $id";
            if ($conn->query($sqlUpdate) === TRUE) {
                echo "Surowiec o ID $id został zaktualizowany.";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        }
    }
    header('Location: viewFodder.php?id=' . $_SESSION["id_pasza"]);

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
    surowce.forEach(function(surowiec) {
        addRawMaterial(surowiec.id, 'button' + surowiec.id , surowiec.nazwa, surowiec.cena, amount, surowiec.min, surowiec.max, surowiec.ilosc, "baza");
        update(surowiec.id, surowiec.cena);
    });
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
    
    function addRawMaterial(id, button, name, price, amount, min, max, ilosc, baza) {
       
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
        materialField.className = 'form-control form-control-sm nameWrap sN';
        materialField.readOnly = true;
        materialField.hidden = true;

        rowS.appendChild(materialField);

        var materialNameField = document.createElement('label');
        materialNameField.innerHTML = name;
        materialNameField.className = 'form-control form-control-sm nameWrap';
        
        rowS.appendChild(materialNameField);

        var priceFieldS = document.createElement('input');
        priceFieldS.type = 'text';
        priceFieldS.readOnly = true;
        priceFieldS.name = 'price[' + id + ']';
        priceFieldS.className = 'form-control form-control-sm labelSize70 ';
        priceFieldS.value = (price !== null && price !== '') ? price : 'Brak danych';
        priceFieldS.hidden = true;
        rowS.appendChild(priceFieldS);

        var minFieldS = document.createElement('input');
        minFieldS.type = 'number';
        minFieldS.name = 'minS[' + id + ']';
        if(min!=null)
        {
            minFieldS.value = min;
        }
        minFieldS.className = 'form-control form-control-sm labelSize60';
        minFieldS.onchange = function() {
            if (parseFloat(minFieldS.value) > parseFloat(maxFieldS.value)) {
                alert('Wartość min nie może być większa niż wartość max');
                minFieldS.value = '';
            }
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
        if(max!=null)
        {
            maxFieldS.value = max;
        }
        maxFieldS.onchange = function() {
            if (parseFloat(maxFieldS.value) < parseFloat(minFieldS.value)) {
                alert('Wartość max nie może być mniejsza niż wartość min');
                maxFieldS.value = '';
            }
            if (parseFloat(maxFieldS.value) > 100 || parseFloat(maxFieldS.value) < 0) {
                alert('Wartość max musi być między 0 a 100');
                maxFieldS.value = '';
            }
        };
        rowS.appendChild(maxFieldS);

        var quantityFieldS = document.createElement('input');
        quantityFieldS.type = 'text';
        quantityFieldS.name = 'quantity[' + id + ']';
        quantityFieldS.id = 'quantity[' + id + ']';
        quantityFieldS.className = 'form-control form-control-sm labelSize60'; 
        quantityFieldS.readOnly = true;
        var contentFieldS = document.createElement('input');
        contentFieldS.type = 'number';
        contentFieldS.name = 'content[' + id + ']';
        contentFieldS.id = 'content[' + id + ']';
        if(ilosc!=null)
        {
            contentFieldS.value = ilosc;
            quantityFieldS.value = (amount*ilosc)/100;
        }
        contentFieldS.className = 'form-control form-control-sm labelSize60'; 
        rowS.appendChild(contentFieldS);
        rowS.appendChild(quantityFieldS);
        contentFieldS.onchange = function(){
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
        

        var removeButton = document.createElement('button');
        removeButton.innerHTML = 'Usuń';
        removeButton.type='button'
        removeButton.className='btn btn-sm btn-outline-secondary action' 
        removeButton.onclick = function() {
            deleteRawMaterial(id);
            if(baza == "baza"){
                var button1 = document.getElementById(button);
                button1.disabled = false;
            }
            button.disabled = false;
            update(id,price);
            
        };
        removeButton.name = 'deletedItem[' + id + ']';
        rowS.appendChild(removeButton);
        document.getElementById('right2').appendChild(rowS);
        button.disabled = true;
            if(baza == "baza"){
                button1.disabled = true;
            }
        
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

    var img = document.createElement('img');
    img.src = "../img/logo.png";

    function printAllElements() {
        var div = document.getElementById("feedLabel");

        var printDiv = document.createElement('div');
        printDiv.id = 'printDiv';
        printDiv.style.width = '105mm';
        printDiv.style.height = '148mm';
        printDiv.style.margin = '0';

        img.alt = "Image";
        img.style.display = 'block';
        img.style.margin = '0 auto';
        printDiv.appendChild(img);

        var printDivElement = document.createElement('div');
        printDivElement.classList.add("fontLabel");

        var eNazwaPasza = document.getElementById("fodderName");
        printDivElement.innerHTML += eNazwaPasza.innerHTML+ ' <br> ';

        var eNazwaZwierze = document.getElementById("animalName");
        printDivElement.innerHTML += eNazwaZwierze.innerHTML+ ' <br> ';

        var eOzn = document.getElementById("mark");
        printDivElement.innerHTML += eOzn.innerHTML+ ' <br> ';

        printDiv.appendChild(printDivElement);

        
        var printDivElementIng = document.createElement('div');
        printDivElementIng.classList.add("feedElements");
        var eSurowiec = document.getElementsByClassName("sN");
        printDivElementIng.innerHTML += "Surowce: "
        for (var i = 0; i < eSurowiec.length; i++) {
            printDivElementIng.innerHTML += eSurowiec[i].value;
            if(i<eSurowiec.length-1)
            {
                printDivElementIng.innerHTML += ', ';
            }
        }
        printDiv.appendChild(printDivElementIng);

        var printDivElementRaw = document.createElement('div');
        printDivElementRaw.classList.add("feedElements");

        var eNazwa = document.getElementsByClassName("drukNazwa");
        var eJednostka= document.getElementsByClassName("drukJednostka");
        var eZawartosc = document.getElementsByClassName("drukZawartosc");
        printDivElementRaw.innerHTML += "Składniki: "
        for (var i = 0; i < eNazwa.length; i++) {
            printDivElementRaw.innerHTML += eNazwa[i].innerHTML;
            printDivElementRaw.innerHTML += ' ' + eZawartosc[i].innerHTML + " " + eJednostka[i].innerHTML;
            if(i<eNazwa.length-1)
            {
                printDivElementRaw.innerHTML += ', ';
            }
        }
        printDiv.appendChild(printDivElementRaw);

        var printDivElement2 = document.createElement('div');
        printDivElement2.classList.add("fontLabel");
        
        var eIlosc = document.getElementById("number");
        printDivElement2.innerHTML += "Waga netto: " + eIlosc.innerHTML + " kg";

        printDiv.appendChild(printDivElement2);

        div.appendChild(printDiv);
        window.print(); 
    }
</script>
</body>
</html>	