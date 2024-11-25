<header class="header trans_300">

		<!-- Top Navigation -->

		<div class="top_nav">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<div class="top_nav_left">free shipping on all orders</div>
					</div>
					<div class="col-md-6 text-right">
						<div class="top_nav_right">
							<ul class="top_nav_menu">

								<!-- Currency / Language / My Account -->

								<li class="currency">
									<a href="#">
										usd
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="currency_selection">
										<li><a href="#">cad</a></li>
										<li><a href="#">aud</a></li>
										<li><a href="#">eur</a></li>
										<li><a href="#">gbp</a></li>
									</ul>
								</li>
								<li class="language">
									<a href="#">
										English
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="language_selection">
										<li><a href="#">French</a></li>
										<li><a href="#">Italian</a></li>
										<li><a href="#">German</a></li>
										<li><a href="#">Spanish</a></li>
									</ul>
								</li>
								<li class="account">
								<?php if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])): ?>
        							<!-- User is not logged in -->
        							<a href="login.php"> <!-- Redirect to login page -->
            							Sign In
        							</a>
    							<?php elseif (isset($_SESSION['user'])): ?>
							        <!-- Regular user is logged in -->
    						    	<a href="#">
            							My Account
            							<i class="fa fa-angle-down"></i>
        							</a>
        							<ul class="account_selection">
            							<!-- Common Sign Out option -->
            							<li><a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i>Sign Out</a></li>
        							</ul>
    							<?php elseif (isset($_SESSION['admin'])): ?>
        							<!-- Admin is logged in -->
        							<a href="#">
            							My Account
            							<i class="fa fa-angle-down"></i>
        							</a>
        							<ul class="account_selection">
            						<!-- Admin Dashboard -->
            							<li><a href="admin_dashboard.php"><i class="fa fa-tachometer" aria-hidden="true"></i>Admin Dashboard</a></li>
            							<!-- Common Sign Out option -->
            							<li><a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i>Sign Out</a></li>
        							</ul>
    							<?php endif; ?>

								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Main Navigation -->

		<div class="main_nav_container">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 text-right">
						<div class="logo_container">
							<a href="#">Purple<span>Star</span></a>
						</div>
						<nav class="navbar">
							<ul class="navbar_menu">
								<li><a href="index.php">home</a></li>
								<li><a href="categories.php">shop</a></li>
								<li><a href="text-book.php">Books</a></li>
								<li class="menu_item">
									<a href="#">Pages</a>
									<ul class="dropdown">
										<li><a href="#">Page 1</a></li>
										<li><a href="#">Page 2</a></li>
										<li><a href="#">Page 3</a></li>
									</ul>
								</li>
								
								<!-- <li><a href="">blog</a></li> -->
								<li><a href="contact.php">contact</a></li>
							</ul>
							<ul class="navbar_user">
								<li>
									<div class="search-box">
      									<button class="btn-search"><i class="fa fa-search"></i></button>
      									<input type="text" class="input-search" placeholder="Type to Search...">
    								</div>
								</li>
								<li><a href="user_profile.php"><i class="fa fa-user" aria-hidden="true"></i></a></li>
								<li class="checkout">
									<a href="#" onclick="toggleCart()">
										<i class="fa fa-shopping-cart" aria-hidden="true"></i>
										<span id="checkout_items" class="checkout_items">0</span>
									</a>
								</li>
								
								<!-- Cart Panel for Right Slide -->
								<div id="cart-panel" class="cart-panel">
									<h3>Your Cart</h3>
									<div id="cart-items-container"></div>
									<button class="close-cart" onclick="toggleCart()">Close</button>
								</div>
								
							</ul>
							<div class="hamburger_container">
								<i class="fa fa-bars" aria-hidden="true"></i>
							</div>
						</nav>
					</div>
				</div>
			</div>
		</div>

	</header>
	<style>
		.search-box{
  			width: fit-content;
  			height: fit-content;
  			position: relative;
		}
		.input-search{
  height: 50px;
  width: 50px;
  border-style: none;
  padding: 10px;
  font-size: 18px;
  letter-spacing: 2px;
  outline: none;
  border-radius: 25px;
  transition: all .5s ease-in-out;
  background-color: transparent;
  padding-right: 40px;
  color:#fff;
}
.input-search::placeholder{
  color:rgba(255,255,255,.5);
  font-size: 18px;
  letter-spacing: 2px;
  font-weight: 100;
}
.input-search:focus{
  width: 300px;
  border-radius: 0px;
  background-color: transparent;
  border-bottom:1px solid rgba(255,255,255,.5);
  transition: all 500ms cubic-bezier(0, 0.110, 0.35, 2);
}
.btn-search{
  width: 50px;
  height: 50px;
  border-style: none;
  font-size: 20px;
  font-weight: bold;
  outline: none;
  cursor: pointer;
  border-radius: 50%;
  position: absolute;
  right: 0px;
  color:#ffffff ;
  background-color:transparent;
  pointer-events: painted;  
}
.btn-search:focus ~ .input-search{
  width: 300px;
  border-radius: 0px;
  background-color: transparent;
  border-bottom:1px solid rgba(255,255,255,.5);
  transition: all 500ms cubic-bezier(0, 0.110, 0.35, 2);
}
	</style>
	<script>
        // Load navbar and footer
        function includeHTML() {
            const navbar = document.getElementById("navbar");
            const footer = document.getElementById("footer");

            fetch("navbar.html")
                .then(response => response.text())
                .then(data => navbar.innerHTML = data);

            fetch("footer.html")
                .then(response => response.text())
                .then(data => footer.innerHTML = data);
        }

        // Call the function on page load
        includeHTML();
    </script>