<div id="content">
    <div id="forms">
        <div class="form" id="blog-form">
            <h1>Blog form</h1>
            <form action="/admin/submission.php" method="POST" enctype="multipart/form-data">

                <label for="entry">Entry:</label><br>
                <textarea name="entry" rows="5" cols="40"></textarea><br>
                <label for="files">Upload files:</label><br>
                
                <div>
                    <b>Valid file types:</b>
                    <ul>
                        <li>Images: <i>jpg/jpeg, png, gif</i> </li>
                        <li>Videos: <i>mp4, mov</i> </li>
                        <li>File size limit: 41943040 bytes </li>
                    </ul>
                </div>

                <input type="file" name="files[]" multiple><br><br>
                <!-- display a list of files that were selected and allow for deletion -->

                <input type="submit" value="Submit entry" name="submit-entry">
            </form>  
        </div>

        <div class="form" id="project-form">
            <h1>Project Form</h1>
            <form action="/admin/submission.php" enctype="multipart/form-data" method="POST">
                <label for="title">Title: </label><br>
                <input type="text" name="title"><br>

                <label for="description">Description: </label><br>
                <textarea name="description" rows="2" cols="30"></textarea><br>

                <label for="link">Link: </label><br>
                <input type="text" name="link"><br>

                <label for="feature">Feature: </label>
                <input type="checkbox" name="feature"><br>

                <input type="submit" value="Submit project" name="submit-project">
            </form>
        </div>
    </div>

    <div id="debug">
        <?php echo $debug; ?>
    </div>
</div>

<a href="/blog">View blog</a><br>
<a href="/projects">View projects</a><br>
<a href="/admin/logout.php">Log out</a>


