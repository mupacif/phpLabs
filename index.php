<!DOCTYPE html>
<html>
  <head>
    <title>VueJs Tutorial - coligo</title>
  </head>
  <body>

  <div id="vue-instance">
  <a href="questions.php"> questions</a>
  <div>
  <ul><li v-for="i in interros"> <a href="#" @click="showQuestion(i.id)"> {{i.nom}}  <span v-if="i.note!=0">~{{i.note}}%</span></a></li></ul>

  <ul><li v-for="q in questions" > {{q.question }} : <pre> {{{q.answer }}}</pre> </li></ul>
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
        elt: [],
        idInterro:-1,
        interro:"",
        question:"",
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

           axios.get("web/interro/"+_id).then(function (response) 
          {
              vm.questions = response.data;
            })
        },
        addQuestion: function()
        {
          if(this.question !== "" && this.answer !== "" && this.idInterro != -1)
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