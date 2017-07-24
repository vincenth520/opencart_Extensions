<?php echo $header; ?>
<div class="container category-row">
	<ul class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
			<?php } ?>
	</ul>
	<div class="row">
    <?php $class = 'col-md-12 col-sm-12 col-xs-12'; ?>
    <div id="content" class="<?php echo $class; ?>"><h2>Quick Order</h2>
	<article class="col-main">
	<style type="text/css">
        td{padding: 5px 10px;color: #000;min-width: 160px;}
        button { background-color: #990e0b;color: #ffffff;border:none;}
        button:hover{background-color:red;color: #ffffff}
	</style>
	<div id="quickorder-form">
	   <h4>IMPORT ORDER Result</h4>	
       <table>
           <tr>
               <td colspan="2">Detail</td>
           </tr>
           <tr>
               <td>Rows Processed</td>
               <td><?php echo $right_num;?></td>
           </tr>
           <tr>
               <td>Rows Skipped</td>
               <td><?php echo $error_num;?></td>
           </tr>
           <tr>
               <td><a href="product/advancedquickorder"><button>UPLOAD ANOTHER</button></a></td>
               <td><a href="checkout/cart"><button>GO TO CART</button></a></td>
           </tr>
       </table>	                                         
    </div>
	  </article>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
