<? ob_start(); ?>
<div class="container text-center">
	<h1 style="color:#22222;">Davinci php BootCamp</h1>
</div>
<?php
session_start();
//session_destroy();
$products_ids = array();
if (filter_input(INPUT_POST,'add_to_cart')){
    if (isset($_SESSION['shopping_cart'])){
        $count = count($_SESSION['shopping_cart']);
        $products_ids = array_column($_SESSION['shopping_cart'],'id');
            if (!in_array(filter_input(INPUT_GET,'id'),$products_ids)){
                $_SESSION['shopping_cart'][$count] = array(
                    'id' =>filter_input(INPUT_GET,'id'),
                    'name' =>filter_input(INPUT_POST,'name'),
                    'price' =>filter_input(INPUT_POST,'price'),
                    'quantity' =>filter_input(INPUT_POST,'quantity'),
                    );
            }
        else{
            for($i = 0 ; $i < count($products_ids);$i++){
                if  ($products_ids[$i] == filter_input(INPUT_GET,'id')){
                    $_SESSION['shopping_cart'][$i]['quantity'] += filter_input(INPUT_POST,'quantity');
                }
            }
            
        }
    }
    else{
        $_SESSION['shopping_cart'][0] = array(
        'id' =>filter_input(INPUT_GET,'id'),
        'name' =>filter_input(INPUT_POST,'name'),
        'price' =>filter_input(INPUT_POST,'price'),
        'quantity' =>filter_input(INPUT_POST,'quantity'),
        );
    }
   
   
}
if(filter_input(INPUT_GET,'action') == 'delete'){
    foreach($_SESSION['shopping_cart'] as $key => $product){
        if ($product['id'] == filter_input(INPUT_GET,'id')){
            unset($_SESSION['shopping_cart'][$key]);
        }    
    }
    $_SESSION['shopping_cart'] = array_values($_SESSION['shopping_cart']);
}
?>

<?php 
$connect = mysqli_connect('localhost','root','01154913425m','cart');
$query  = 'SELECT * FROM products ORDER BY id ASC';
$result = mysqli_query($connect,$query);
$num_rows = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shooping Cart</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link rel="stylesheet" href="cart.css" />
    <script type="text/javascript" src="bootstrap.min"></script>
</head> 
<body>
  <div class="container">
  <div class="wrrap">
   <?php
if ($num_rows > 0){
    while($products = mysqli_fetch_assoc($result)){
      ?>
      <div class="col-sm-4 col-md-3">
         <form method="post" action="?action=add&id=<?php echo $products['id']; ?>">
          <div class="products">
             <div class="row">
<img src="<?php echo $products['image']; ?>" style="width:200px;height:200px;"/>
 </div>
         
              <h4 class="text-info"><?php echo $products['name']; ?></h4>
              <h4>$ <?php echo $products['price']; ?></h4>
              <input class="form-control" type="text" name="quantity" value="1"/>
              <input type="hidden" name="name" value="<?php echo $products['name'] ?>"/>
              <input type="hidden" name="price" value="<?php echo $products['price'] ?>"/>
              <input type="submit" class="btn btn-info submito" name="add_to_cart" value="Add To Cart" />           
          </div>
          </form>
      </div>
      <?php
    }
}
?>
     <div style="clear:both;"></div>
     <br/>
     <div class="table-responsive">
         <table class="table">
             <tr><th colspan="5"><h3>Order Details</h3></th></tr>
             <tr>
                 <th with="40%">Product Name</th>
                 <th with="10%">Quantity</th>
                 <th with="20%">Price</th>
                 <th with="15%">Total</th>
                 <th with="5%">Action</th>
             </tr>
             <?php 
                if(!empty($_SESSION['shopping_cart'])){
                    $total=0;
                    foreach($_SESSION['shopping_cart'] as $key => $product){
                     ?>
                     <tr>
                         <td><?php echo $product['name']; ?></td>
                         <td><?php echo $product['quantity']; ?></td>
                         <td>$ <?php echo $product['price']; ?></td>                         
                         <td>$ <?php echo number_format($product['quantity'] * $$product['price'],2); ?></td>      
                         <td>
                         <a href="cart.php?action=delete&id=<?php echo $product['id']; ?>">
                             <div class="btn btn-danger">Remove</div>
                             </a>
                         </td>                  
                     </tr> 
                     <?php
                        $total = $total + ($product['quantity'] * $product['price']);
                    }
                        ?>
                        <tr>
                            <td colspan="3" align="right">Total</td>
                            <td align="right">$ <?php echo number_format($total,2); ?></td>
                            
                        </tr>
                        <tr>
                         <td colspan="5">
                         <?php
                          if (isset($_SESSION['shopping_cart'])){
                            if(count($_SESSION['shopping_cart']) > 0){
                                ?>
                                <a href="#" class="button btn btn-outline-success">CheckOut</a>
                                <?php
                            }
                           }  
                            ?>
                            </td>
                        </tr>
                     <?php  
                    }
                   ?>
         </table>
     </div>
      </div>
    </div> 
</body>  
<!-- huushhh Thi's Script By Davinci BootCamp--> 
</html>
<? ob_end_flush(); ?>