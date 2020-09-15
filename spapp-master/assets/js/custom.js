$(document).ready(function() {

  $("main#spapp > section").height($(document).height() - 60);

  var app = $.spapp({pageNotFound : 'error_404'}); // initialize

  // define routes
 
  
     app.route({
      view: 'about',
       load: 'about.html' });

       app.route({
        view: 'user_ticketsPG',
         load: 'user_ticketsPG.html' });

       app.route({
        view: 'tickets',
         load: 'tickets.html' });

        
           app.route({
            view: 'contact',
             load: 'contact.html' });

             app.route({
              view: 'inbox',
               load: 'inbox.html' });
  

  // run app
  app.run();

});