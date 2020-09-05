$(document).ready(function() {

  $("main#spapp > section").height($(document).height() - 60);

  var app = $.spapp({pageNotFound : 'error_404'}); // initialize

  // define routes
 
  

     app.route({
      view: 'about',
       load: 'about.html' });

       app.route({
        view: 'tickets',
         load: 'tickets.html' });

         app.route({
          view: 'payment',
           load: 'payment.html' });

           app.route({
            view: 'contact',
             load: 'contact.html' });
  

  // run app
  app.run();

});