<!-- banner -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<span style="float:left; margin-top: 5px;">
		<img class="logo" src="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/images/tag_logo.png" style="height: 2.8em;">
	</span>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
			<li class="nav-item active">
        <a class="nav-link" href="<?= $this->VARS['link_home'] ?>">ホーム <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= $this->VARS['link_about'] ?>">企業概要</a>
      </li>
			<li class="nav-item">
        <a class="nav-link" href="<?= $this->VARS['link_contact'] ?>">お問合せ</a>
      </li>
			<li class="nav-item">
        <a class="nav-link" href="<?= $this->VARS['link_eregister'] ?>">車両査定</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>
<!-- //banner -->
