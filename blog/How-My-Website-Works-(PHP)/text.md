My website is a **standalone PHP application** meaning no structural frameworks or libraries were employed to make it. It is part of the LAMP stack which was how I wanted to make my site. In this blog post, I will be describing how my website works from the backend perspective, of course with limited details for security purposes.  

## Database Entities
If you look at the navigation bar, you will see the three main components of my website: *projects*, *status updates*, and *blogs*. One feature I was keen on having was the ability to readily insert any of these entities into my website instead of manually updating, for instance, an HTML file each time. This requires a database and an admin site. The admin site would serve as the interface for me to insert the content with predefined SQL queries. I was able to achieve this goal and even automate some parts (I will eventually have to scrap this design which I will explain why later). 

## Templating
On every page of my website, you will see that it has the same header and footer. The pages are also structured: for example, the status updates are aligned vertically and always followed with a timestamp. Rather than hardcoding each page and mix the backend code with the frontend code, I created a template system. If you are familiar with the Django framework, the system is very similar to the concept of views and templates. I have a colllection of templates for each of my page, as well as templates for the layout of each entity in the page, and each page's folder has an `index.php` which serves as the view. The `index.php` file is what you see every time you load a page. It uses SQL queries to get appropiate data from the database, uses the template to insert the data in the right spot and returns the HTML that you see.

## Deployment
I am hosting the website with Heroku as a PHP application with the PostgreSQL addon database they provide. I won't explain the whole process of eployment with Heroku. All you need to know if that you can link a Git/Github repository (where all the code for your website is stored) to Heroku and tell Heroku to deploy it. Heroku will build everything from the linked repository and provide a URL for your deployment.  
  

Here is where I will have to scrap my design. Whenever I create a blog, it will automatically create folder containing all the blog's files, essentially changing the codebase. However, it only updates the current Heroku deployment, not the linked Github repository. So everytime I make changes to the code and deploy with the Github repository, Heroku will take a fresh clone of Github code. That means all the files that were automatically created in the previous Heroku deployment are gone (Josh W. Comeau describes a similar issue with using third-party servers in his [blog](https://www.joshwcomeau.com/blog/how-i-built-my-blog/) under "Downsides"). As a result, I had to remove the automated processes in my code and now have to manually commit the folders/files with Github. With automation, I was able to insert a blog into the database and it will atuomatically create the folder for me. Then using SQL queries, the blog page will be able to display the new blog information from the database. Because I am still using SQL queries to get blog information, I will still have to insert with the database in order for it to show up on the blog page.

**Here is how I would post a blog:**

*Upload files*  

1. I will create a folder for the blog and put the necessary files.  
![test][image1]  
[image1]: https://i.imgur.com/zm48rQN.png
2. Then I will commit to Git and push into Github.
![test][image2]  
[image2]: https://i.imgur.com/GVVPEp5.png
3. I will deploy my Github repository with Heroku.
![test][image3]  
[image3]: https://i.imgur.com/KF4Sqd5.png    
  
*Insert into database*

4. Then I will have to insert it into the database.
![test][image4]  
[image4]: https://i.imgur.com/oIeoo7l.png

