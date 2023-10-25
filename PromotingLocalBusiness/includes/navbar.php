
<style>
    * {
        margin: 0px;
        padding: 0px;
    }
    nav {
        background-color: darkgray;
        display: flex;
        align-items: center;
        margin-bottom: 2vh;
        position: fixed;
        width: 100%;
    }
    
    nav > * {
        margin-left: 15vw;
        font-size: large;
        font-weight: bold;
    }
    nav > div {
        display: flex;
        align-items: center;
    }
    nav > a {
        text-decoration: none;
        color: white
    }
    nav > div > a {
        text-decoration: none;
        color: white;
        margin-left: 0.25vw;
    }
    nav > div > a:hover {
        color: lightgray;
    }
    nav > a:hover {
        color: lightgray;
    }
    #nav-image {
        height: 3vh;
        border: 2px solid black;
        border-radius: 3vh;
    }

</style>

<nav>
    <a href="../main/home.php">Home</a>
    <a href="../main/discover2.php">Discover</a>
    <a href="../main/demoPost.php">Post</a>
    <div>
        <a href="../main/profile.php"><img src="<?= $user['profile_image'] ?>" alt="" id="nav-image"></a>
        <a href="../main/profile.php"><?= $user["username"] ?></a>
    </div>
</nav>