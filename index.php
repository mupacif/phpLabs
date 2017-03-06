<!DOCTYPE html>
<html>
  <head>
    <title>VueJs Tutorial - coligo</title>
    <style type="text/css">
      /* The snackbar - position it at the bottom and in the middle of the screen */
#snackbar {
    visibility: hidden; /* Hidden by default. Visible on click */
    min-width: 250px; /* Set a default minimum width */
    margin-left: -125px; /* Divide value of min-width by 2 */
    background-color: #333; /* Black background color */
    color: #fff; /* White text color */
    text-align: center; /* Centered text */
    border-radius: 2px; /* Rounded borders */
    padding: 16px; /* Padding */
    position: fixed; /* Sit on top of the screen */
    z-index: 1; /* Add a z-index if needed */
    left: 50%; /* Center the snackbar */
    bottom: 30px; /* 30px from the bottom */
}

/* Show the snackbar when clicking on a button (class added with JavaScript) */
#snackbar.show {
    visibility: visible; /* Show the snackbar */

/* Add animation: Take 0.5 seconds to fade in and out the snackbar. 
However, delay the fade out process for 2.5 seconds */
    -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
    animation: fadein 0.5s, fadeout 0.5s 2.5s;
}

/* Animations to fade the snackbar in and out */
@-webkit-keyframes fadein {
    from {bottom: 0; opacity: 0;} 
    to {bottom: 30px; opacity: 1;}
}

@keyframes fadein {
    from {bottom: 0; opacity: 0;}
    to {bottom: 30px; opacity: 1;}
}

@-webkit-keyframes fadeout {
    from {bottom: 30px; opacity: 1;} 
    to {bottom: 0; opacity: 0;}
}

@keyframes fadeout {
    from {bottom: 30px; opacity: 1;}
    to {bottom: 0; opacity: 0;}
}
    </style>
  </head>
  <body>

  <div id="vue-instance">
  <div id="snackbar">{{snackbar}}</div>
  <a href="questions.php"> questions</a>
  <div>
  <ul><li v-for="i in interros"> <a href="#" @click="showQuestion(i.id)"> {{i.nom}}  <span v-if="i.note!=0">~{{i.note}}%</span></a></li></ul>

  <ul><li v-for="q in questions" > {{q.question }} :<textarea id="item{{q.id}}"> {{{q.answer }}}</textarea><button @click="setQuestion(q.id)">save</button></li>  </ul>  <br>
  <textarea type="text" v-model="question1" placeholder="question"> </textarea> <br>
  <textarea  type="text" v-model="answer1" placeholder="answer"  ></textarea> <br>
  <button @click="">add Question</button>
  <h1> Ajoutez une interro </h1>
<input type="text" v-model="interro" placeholder="chapitre" :disabled="disabledInterro">
<button type="text" @click="addInterro" :disabled="disabledInterro"> add </button>
</div>
<div>
<h1> ajouter questions</h1>
  <textarea type="text" v-model="question" placeholder="question" :disabled="disabledQuestion" rows="5" cols="100"> </textarea> <br>
  <textarea  type="text" v-model="answer" placeholder="answer" :disabled="disabledQuestion"   rows="5" cols="100"></textarea> <br>



  <button v-on:click="addQuestion" :disabled="disabledQuestion" >Add</button>
</div>

    <ul>
    <li v-for="item in elt" >
    {{ item.question}} :  <pre>  {{{ item.answer}}}</pre>
    </li>
  </ul>
   </div>


  <script src="http://cdn.jsdelivr.net/vue/1.0.16/vue.js"></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

  <script>
    // our VueJs instance bound to the div with an id of vue-instance


    var vm = new Vue({
      el: '#vue-instance',
      data:{
        id:-1,
        snackbar:"",
        elt: [],
        idInterro:-1,
        interro:"",
        question:"",
           interro1:"",
        question1:"",
        answer:"",
        interros: [],
        questions:[]
      },
      created:function()
        { 
           
          axios.get("web/interros").then(function (response) 
          {
              vm._data.interros = response.data;
            })

          },

      methods:
      {
        showQuestion:function(_id)
        {
          this.id=_id;

           axios.get("web/interro/"+_id).then(function (response) 
          {
              vm.questions = response.data;
            })
        },
        addQuestion: function()
        {
          if(this.question !== "" && /*this.answer !== "" &&*/ this.idInterro != -1)
          {
             axios.post('web/addQuestion', {question: this.question, answer: this.answer, idInterro : this.idInterro })
          .then(function (response) {
            console.log(response)
          })
          .catch(function (error) {
            console.log(error);
          }); 

                         this.elt.push({question:this.question,answer:this.answer});
             this.question= this.answer = "";
         
          }
          else
            alert("empty")
        },
        setQuestion:function(_idQuestion)
        {
          _answer = document.getElementById("item"+_idQuestion).value;
          // this.snack(document.getElementById("item"+_idQuestion).value);
           this.snack(_idQuestion)
           axios.post('web/setQuestion', {idQuestion: _idQuestion, answer: _answer}).then(function (response) {
            // vm.snack(response.data);

            vm.refresh()
          })
          .catch(function (error) {
            console.log(error);
          });

        },
        addInterro:function()
        {
          if(this.interro !== "")
          {
          
            axios.post('web/addInterro', {nom: this.interro })
          .then(function (response) {
            vm._data.idInterro = response.data.id;

            vm.refresh()
          })
          .catch(function (error) {
            console.log(error);
          });
        }
        },
        refresh:function()
        { 
           
          axios.get("web/interros").then(function (response) 
          {
              vm._data.interros = response.data;
            })

          },
          snack: function(msg) {
            this.snackbar = msg;
    // Get the snackbar DIV
    var x = document.getElementById("snackbar")

    // Add the "show" class to DIV
    x.className = "show";

    // After 3 seconds, remove the show class from DIV
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}
      },
      computed:
      {
        disabledInterro:function()
        {return this.idInterro!=-1},
        disabledQuestion:function()
        {return this.idInterro==-1},
       

      }
    });




  </script>

  </body>
</html>