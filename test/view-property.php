<?php
session_start();
if (!isset($_SESSION["email"])) {
    header("location:../index.php");
}

include "navbar.php";
include "../config/config.php";

?>

<?php


	$property_id=$_GET['property_id'];
    $sql="SELECT * from add_property where property_id='$property_id'";
	$query=mysqli_query($db,$sql);

	if(mysqli_num_rows($query)>0)
{
    while($rows=mysqli_fetch_assoc($query)){
    $sql2="SELECT * FROM property_photo where property_id='$property_id'";
    $query2=mysqli_query($db,$sql2);

    $rowcount=mysqli_num_rows($query2);
?>
<style>
    td > h3{
        font-size: medium;
    }
    .btn{
        background-color: blue;
        color:white;
        width:120%;
        padding-inline: 14px;
        padding-block: 5px;
        border: 1px solid black;
        border-radius: 10px;
    }
    .btn:hover{
        opacity: 80%;
        color:white;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">


            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner" role="listbox">
                    <?php
                    for ($i = 1; $i <= $rowcount; $i++) {
                        $row = mysqli_fetch_array($query2);
                        $photo = $row['p_photo'];
                    ?>

                        <?php
                        if ($i == 1) {
                        ?>
                            <div class="item active">
                                <img class="d-block img-fluid" src="../owner/<?php echo $photo ?>" alt="First slide" width="100%" style="max-height: 600px; min-height: 600px;">
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="item">
                                <img class="d-block img-fluid" src="../owner/<?php echo $photo ?>" alt="First slide" width="100%" style="max-height: 600px; min-height: 600px;">
                            </div>

                    <?php
                        }
                    }
                    ?>

                </div>

                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>

        </div>
        <div class="col-sm-6">
            <center>
                <h2><?php echo $rows['property_type'] ?></h2>
            </center>
            <div class="row">
                <div class="col-sm-6">

                    <div class="row">
                        <div class="col-sm-6">
                            <table>
                                <tr>
                                    <td>
                                        <h3>Country:</h3>
                                    </td>
                                    <td>
                                        <h3><?php echo $rows['country']; ?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h3>Province:</h3>
                                    </td>
                                    <td>
                                        <h3><?php echo $rows['province']; ?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h3>Zone:</h3>
                                    </td>
                                    <td>
                                        <h3><?php echo $rows['zone']; ?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h3>District:</h3>
                                    </td>
                                    <td>
                                        <h3><?php echo $rows['district']; ?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h3>City:</h3>
                                    </td>
                                    <td>
                                        <h3><?php echo $rows['city']; ?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h3>VDC/Municipality:</h3>
                                    </td>
                                    <td>
                                        <h3><?php echo $rows['vdc_municipality']; ?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h3>Ward No.:</h3>
                                    </td>
                                    <td>
                                        <h3><?php echo $rows['ward_no']; ?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h3>Tole:</h3>
                                    </td>
                                    <td>
                                        <h3><?php echo $rows['tole']; ?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h3>Contact No.:</h3>
                                    </td>
                                    <td>
                                        <h3><?php echo $rows['contact_no']; ?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h3>Estimated Price:</h3>
                                    </td>
                                    <td>
                                        <h3>Rs.<?php echo $rows['estimated_price']; ?></h3>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <table>
                        <tr>
                            <td>
                                <h3>Total Rooms:</h3>
                            </td>
                            <td>
                                <h3><?php echo $rows['total_rooms']; ?></h3>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h3>Bedrooms:</h3>
                            </td>
                            <td>
                                <h3><?php echo $rows['bedroom']; ?></h3>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h3>Living Room:</h3>
                            </td>
                            <td>
                                <h3><?php echo $rows['living_room']; ?></h3>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h3>Kitchen:</h3>
                            </td>
                            <td>
                                <h3><?php echo $rows['kitchen']; ?></h3>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h3>Bathroom:</h3>
                            </td>
                            <td>
                                <h3><?php echo $rows['bathroom']; ?></h3>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h3>Booked:</h3>
                            </td>
                            <td>
                                <h3><?php echo $rows['booked']; ?></h3>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h3>Description:</h3>
                            </td>
                            <td>
                                <h3><?php echo $rows['description']; ?></h3>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <a href="javascript:history.back()" class="btn btn-secondary">Back</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<?php 
}}
else{
    echo "Page not found..";
}