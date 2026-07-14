<?php
$sm_share = "";
include '../includes/header.php';
include '../includes/email-functions.php';
include 'functions.php';
if(logged_in() AND user_admin())
    {
        if(isset($_GET['store']))
            {
                $store_name = $_GET['store'];

                $sql = "SELECT
                            *
                        FROM
                            retail_request
                        WHERE
                            store_name = '$store_name'
                        ";
                // echo $sql; die();
                $statement = $db->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll();
                foreach($result as $row)
                    {
                        $name = $row['name'];
                        $email = $row['email'];
                        $website = $row['website'];
                        $fb = $row['fb'];
                    }
            }

        if(isset($_POST['submit']))
            {
                $name = test_input_pw($_POST['name']);
                $email = test_input_pw($_POST['email']);
                $agreement = htmlentities($_POST['agreement']);
                $store_name = test_input_pw($_POST['store_name']);
                 
                $sql = "INSERT INTO 
                            retail_agreements (name, email, agreement, store_name, date)
                        VALUES
                            (:name, :email, :agreement, :store_name, :date)
                        ";

                $statement = $db->prepare($sql);
                
                $statement->bindParam(':name'	        ,	$name       , PDO::PARAM_STR);
                $statement->bindParam(':email'	        ,	$email      , PDO::PARAM_STR);
                $statement->bindParam(':agreement'	    ,	$agreement  , PDO::PARAM_STR);
                $statement->bindParam(':store_name'     ,	$store_name , PDO::PARAM_STR);
                $statement->bindParam(':date'      	    ,	$date       , PDO::PARAM_STR);
                try
                    {
                        $statement->execute();
                        $success = "<div class='container alert alert-success'>Agreement has been sent to store owner. Return to <a href='retail-applications'>retail applications</a></div>";
                        send_agreement($name, $email);
                    }
                catch(PDOException $e)
                    {
                        echo $e;
                        $failed = "<div class='container alert alert-danger'>Registration failed. Please try again.</div>";
                    }

            $db = null;
            }
?>
<script>
    tinymce.init({
      selector: '#agreement'
    });
 </script>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Send Agreement</h1>
  </div>
</div>
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12">
            <?php
                if(isset($success))
                    {
                        echo $success;
                    }
                else
                    {
                        echo "";
                    }
            ?>
            <h2>Agreement Template</h2>
            <h4>Retailer details:</h4>
            <ul>
                <li>Name: <strong><?=$name?></strong></li>
                <li>Store Name: <strong><?=$store_name?></strong></li>
                <li>Email: <strong><?=$email?></strong></li>
                <li>Website: <strong><?=$website?></strong></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <form action="" method="post">
                <textarea name="agreement" id="agreement" rows="150">
                    <p style="text-align: center;" data-mce-style="text-align: center;"><strong>Retailer Wholesale</strong></p><p style="text-align: center;" data-mce-style="text-align: center;"><strong> License Agreement</strong></p><p> This agreement is made as of the day of , 2021, by and ISABELLA ART AND INTERIORS, LLC, a Georgia Limited Liability Company (“Seller”) , and __________________ , _ ("Retailer"). Seller and Retailer are together known as the “Parties”. </p><p style="text-align: center;" data-mce-style="text-align: center;"><strong>WITNESSETH: </strong></p><p><strong>WHEREAS</strong>, Seller creates, designs and sells a variety of unique and exclusive Trademarked line of paper products (the “Products”) under the Decoupage Queen name (the “Brand”, including but not limited to those presented on its website (www.decoupagequeen.com) (the “Website”) Whereas. Retailer desires to be authorized and licensed to purchase Products from Seller at wholesale prices in order to sell the Products to its own customers. Together the Parties together willingly enter into this Wholesale License Agreement (the “License Agreement” or “Agreement”); </p><p>The term Retailer and Seller are defined to both include each entity’s employees, agents, owners, officers, members, managers, contractors, partners, representatives or assigns as related to their specific entity. </p><p><strong>WHEREAS</strong>, the Parties agree that the terms of the sale of Seller’s Products to Retailer shall be governed by this License Agreement; </p><p><strong>NOW, THEREFORE</strong>, in consideration of the mutual covenants and promises hereinafter set-forth, Seller and Retailer agree as follows: </p><p><strong>1. Payment Terms</strong>. Retailer may purchase products using Visa, MasterCard, Discover, American Express, Wire Transfer or Venmo. Payment is due, in full, at the time that the order is placed on the Website. The prices of the Products are published on Seller’s Order Form, and all prices are subject to change without notice. Seller will provide Retailer with an invoice for goods purchased, excluding items that may be unavailable or backordered, and shipping costs. If payment is made via credit card, a 3.5% processing fee will be added. </p><p><strong>2. Shipping.</strong> Seller will ship all Products via UPS or USPS, unless otherwise requested in writing. If not determined in the on-line order process, the shipping and packing costs shall be calculated after the order is placed and will be added to the invoice provided, based upon the weight and destination of the Products purchased and as determined by the shipping method requested. If not determined in the on-line order process, shipping cost will be approved and paid by the Retailer prior to shipment of the Product. </p><p><strong>3. Returns/Claims Policy.</strong> Due to significant discounts, all sales are final and Seller will not accept returns of any Products, except as provided in subsection (b) below. </p><p>(a)If Retailer receives a Product that is defective or incorrect in some way, Retailer shall notify Seller of the specific problem in writing within seven (7) days of receiving such defective or incorrect Product. </p><p>(b)Seller will have the right and exclusive discretion to either ship a replacement to Retailer or credit the Retailer for any portion of the damaged, defective or incorrect Product. If required, Seller will reimburse Retailer for any costs incurred for return postage of defective Product. </p><p><strong>4. Termination.</strong> This Agreement may be terminated by either party at any time and for any reason upon the giving of thirty (30) days prior written notice to the other party or immediately in case of Default as outlined herein. </p><p><strong>5. Limitations on Retailer’s Sales of the Products. </strong></p><p>(a) The Retailer agrees that the prices as indicated on the Sales Order Form are confidential and are established between Retailer and Seller at the time of order, and are subject to change at any time prior to the Seller placing a new order , including the structure of any tiered or volume discounts. </p><p>(b) The Retailer agrees not to sell the Products to any other third party, wholesaler or reseller. Retailer agrees not to market or sell the Products through any auction websites using a bidding process, including, without limitation to Ebay.com, Craigslist.com, or through co-op sales. Retailer agrees not to sell the Products on Amazon.com, Wal-Mart.com or any third party selling sites. The use of Etsy.com as a sales platform is approved. Retailer may use their company website or Facebook pages as a sales platform and other e-commerce platform such as Shopify, Big Commerce or Wix. Sites other than those specifically listed herein must be approved in advance. </p><p>(d) Any Products sold or provided by Seller to Retailer must use and reference the Decoupauge Queen name (Trademark Pending) in the product listing or any related advertising of the Product. Products may not be repackaged or resold under other names or brands including and name or brand of Retailer or Retailers associated product lines or related companies. </p><p>(e) Any sales of DECOUPAGE QUEEN Products by a Retailer via Retailer’s website ore-commerce distribution channel shall be made to customers within the Retailer’s normal sales channels (except those specifically prohibited above). The Retailer shall be solely responsible for complying with all applicable taxes, import and export laws and regulations relating to sales and shipments of all products offered outside the United States. </p><p>(f) Seller does not offer territory protection or exclusive markets to Retailers. Retailer understands that the License Agreement is not exclusive to Retailer and that Seller may agree to make License Agreements with other retailers. </p><p>(g) The Retailer agrees to use commercially reasonable efforts to promote the distribution and sale of the Products. Seller may from time to time offer and make available marketing materials to be used in a manner that is positive and promotes the distribution and sale of the Products. </p><p>(h) The Retailer may have the ability to preview, access or pre-order new Products that may have not been released to the general public for sale. Retailer may not share new Product with the public in any fashion including Retailer’s website, Facebook, Etsy, Instagram or other related advertising or sales channels unless Seller authorizes the Products release. Similarly, Retailer may not list, give or sell any new Product until the Seller has indicated, in writing, that the specified Product has been released for sale to the general public. </p><p>(i) Seller reserves the right to stop selling the Products to Retailer if Retailer resells the Products to its customers at a price per unit below Seller’s Minimum Retail Price as listed on the Sales Order Form. Seller may modify or change the Suggested Retail Prices from time to time with written notice to the Retailer. The Retailer acknowledges and agrees that such restrictions on the prices of the Products are reasonable and in the interest of Retailer and Seller for the promotion of the Products and the maintenance of the image and quality of the Products. </p><p>(j)The Retailer agrees not to offer the Products for sale via any emails, text messages, instant messages or other electronic messaging where such messages are sent in bulk, indiscriminately or to parties that did not solicit the message (also known as SPAM messages). </p><p>(k)The Retailer agrees not to make any statements about Seller to the media, including newspapers, magazines, radio, television, documentaries, interviews, internet sites, online forums, blogs, public speaking, expos, trade shows, or events, unless such statements have been approved in advance in writing by Seller. </p><p>(l) The Retailer may use social media sites such as Instagram and Facebook to share project photos, new product release announcements, stock replenishment, customer reviews, workshop announcements and other marketing related posts pertaining to DECOUPAGE QUEEN as long as such posts are positive in nature and that Sellers Brand is referenced or given credit. </p><p>(m) The name Decoupage Queen, Decoupage Queen Paper, decoupagequeen.com and all related Facebook and Instagram pages and groups are the exclusive property of Seller and may not be used other than as provided in this Agreement. Decoupage Queen is pending Trademark protection. </p><p>(n) Sellers Products are exclusive copyrighted designs. Products may not be replicated, duplicated, copied or reproduced in part or in whole without the express written permission of Seller. </p><p>(o) The Retailer agrees to sell, advertise, market, present and use the Products in a professional manner. Retailer is prohibited from using products in a unprofessional, degrading, manner for any use for which the papers were not intended. The Products may not be used in any form of pornography, adult content or display containing other sexual acts or contexts. </p><p>(p) Seller will provide Retailer with low resolution images to be used for listing the items for sale on Retailer’s websites. Retailer will not use these images to reproduce, replicate, or copy in any other way for any other purposes other than marketing the items for sale. Retailer acknowledges that these images are the intellectual property of Seller. Seller retains all rights to usage and distribution of images. </p><p><strong>6. Notices.</strong> All notices, demands and other communications hereunder shall be in writing and shall be deemed to have been duly given on the date of service if served personally on the party to whom notice is to be given, or on the third business day after mailing if mailed to the party to whom notice is to be given by first class mail, postage prepaid, certified, return receipt requested, addressed to the party at the address listed at the end of this Agreement. Either party may change addresses for purposes of this paragraph by giving the other party notice of the new address in the manner described herein. </p><p><strong>7. Indemnity.</strong> Each party will defend, indemnify, save and hold harmless the other party, its officers, directors, agents, and employees from any and all third-party claims, demands, liabilities, judgments, damages, costs or expenses, including reasonable attorney’s fees (“Liabilities”), resulting from the indemnifying party’s breach of any material duty, representation, or warranty contained in this Agreement, except there shall be no obligation to indemnify, defend, save and hold harmless where Liabilities result from the gross negligence or knowing and willful misconduct of the other party. </p><p><strong>8. Breach of the Terms and Conditions of This Agreement.</strong> Seller retains the right to terminate this License Agreement for any breach of the terms and conditions herein. Seller may provide Retailer a notice to correct a default of any of the terms or conditions contained in this Agreement. If Retailer does not remedy a default in a seven-day period, Seller shall have the right to immediately terminate this License Agreement. Seller recognizes that breach of the terms and conditions of this Agreement could cause severe economic harm to Seller. For any acts of gross negligence, intentional or willful misconduct on the part of Retailer, Seller may seek monetary damages from Retailer. Seller agrees to pay Sellers legal costs for enforcement of this Agreement or for any acts of gross negligence, intentional or willful misconduct. </p><p><strong>9. Miscellaneous.</strong> This Agreement may be simultaneously executed in any number of counterparts, each of which when so executed and delivered shall be deemed an original, but all of which together shall constitute one and the same instrument. As used in this Agreement, the singular number shall include the plural, the plural the singular, and the use of the masculine shall include, where appropriate, the feminine and neuter. This Agreement shall be governed by and construed in accordance with the laws of Maine. If any provision of this Agreement is determined to be invalid or unenforceable, it shall not affect the validity or enforcement of the remaining provisions hereof. </p><p><strong>10. Taxes/Expenses.</strong> Retailer agrees to pay all taxes, including but not limited to use taxes, VAT, and all other expenses owed in connection with the sale of the Products to its customers. Retailer warrants that all Products purchased from Seller are for resale purposes and not for personal use. </p><p><strong>11. Relationship of Parties.</strong> The parties do not intend to enter into a joint venture, and the parties agree that Retailers not an agent or affiliate of Seller. </p><p><strong>12. Warranties/Damages.</strong> Seller makes no warranties, either express or implied, with respect to the design, manufacture, quality, or merchantability of the Products, or the fitness of the Products for a particular purpose. Seller shall not be liable for any direct, indirect, punitive, special, incidental, or consequential damages, including without limitation, lost revenues or lost profits arising out of, or in any way connected with, Retailer’s use of the Products. </p><p><strong>13. Agreement.</strong> The parties agree to be bound by all of the terms and conditions set forth herein. </p><p style="text-align: center;" data-mce-style="text-align: center;"><strong>SIGNED AGREEMENT MUST BE RECEIVED BEFORE INITIAL ORDER IS PLACED</strong></p><p> ______________________________________________________________________________________________________ </p><p>Signature Page SELLER: </p><p>Company: ISABELLA ART AND INTERIORS, LLC By: ________________________________________ </p><p>Printed Name:_______________________________ </p><p>Title: ______________________________________ </p><p>Date: _____________________________________</p><p><br data-mce-bogus="1"></p><p>RETAILER: Company: ___________________________________ </p><p>By: _________________________________________ </p><p>Printed Name: _______________________________ </p><p>Title: _______________________________________ </p><p>Date: _______________________________________ </p>
                </textarea>
                <input type="hidden" name="email" value="<?=$email?>">
                <input type="hidden" name="name" value="<?=$name?>">
                <input type="hidden" name="store_name" value="<?=$store_name?>">
                <button class="btn btn-info mt-3" name="submit" style="min-width: 100%;">Send Agreement</button>
            </form>
        </div>
    </div>
</div>
<?php
    }
    else
        {
            echo "<div class='container'><div class='alert alert-danger'><strong>You do not have permission to access this page</strong></div></div>";
        }

    include '../includes/footer.php';
?>