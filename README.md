Well, between my earlier blogging of photos and now I've been writing a little PHP REST framework. It has come from my need for a REST interface for my iPhone app for my major project. After much searching, I was unable to find a decent REST framework, or at least something that was easy to understand and use. 

I began to follow a tutorial I found [here](http://www.lornajane.net/posts/2012/building-a-restful-php-server-understanding-the-request), which served as the complete base of the project.  That blog post doesn't show you much, only a bunch of simple code snippets from a working server. I put some of these to use, writing my own implementations. The one thing this tutorial was missing was working with models - but that's fine, the tutorial was just about REST.

I began wondering about CoreData on iOS and the magical ways it does things to data. It transforms the data and uses the NSManagedObject class along with a NSManagedContext to do all the database magic. I thought to myself - "why not take this approach to the issue". So that's what I did exactly. Now, I had multiple DB options, but I opted to use SQLite3 simply because I could take the DB around with me using git. 

I wrote a standard class for handing all the data objects called `NWManagedObjectModel`. This model holds the methods for saving and purging. Any new data model can be created off this class. The subclass can define it's own purging methods, saving methods, or whatever else it needs. The main idea is that each model simply hold the variables and their types.  

When an object is saved, an SQL query checks to see if a table has been created, and if it hasn't will create one. The table will be created to identically replicate the PHP object model/class object type you are saving. Once the table is created, it now inserts/updates the object to the database as  a new row. 

Creating objects and adding them to the DB is relatively simple - Simply create a new PHP object for the model/entity you want, and call the save() method on the object. 

Retrieving objects is equally easy - Simply create a `NWManagedObjectContext` object, and call either `getEntitiesForStatement($entity, $statement)`, calling your own custom SQL statement, OR `getEntityForInstance($entity, $instanceID)`. These return an array full of the entities/php objects you requested. 
As for the REST and how it handles these different object model types - It's really transparent. All you need to do is define a controller for the model, you don't even need to define any methods on the class (provided you subclass `RoutingController`), all the work is done within the RoutingController class. 

Anyways, that's probably enough late night rambling - plus it's a school night. 
  

<a rel="license" href="http://creativecommons.org/licenses/by-nc/3.0/deed.en_US"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc/3.0/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">NWRestful</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="http://www.nickwhyte.com" property="cc:attributionName" rel="cc:attributionURL">Nick Whyte</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc/3.0/deed.en_US">Creative Commons Attribution-NonCommercial 3.0 Unported License</a>.