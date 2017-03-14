<!DOCTYPE html>
<html>
  <head>
    <title> questions </title>
  </head>
  <body>

  <?php 
if(!isset($_GET["id"]) || empty($_GET["id"]))
        {$host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'matiere.html';
    echo "<script>window.location.replace('http://$host$uri/$extra')</script>";
    } 
else
  $id = htmlspecialchars($_GET["id"]);

?>

 <div id="vue-instance">
   <a href="index.php?id={{idMatiere}}"> ajout qestions</a>
   <ul><li v-for="i in interros"> <a href="#" @click="showQuestion(i.id)">  {{i.nom}}</a></li></ul>
   <div v-if="currentQuestion">
   {{ idInterro+1}} / {{ questions.length }} 
   <br>
    {{currentQuestion.question}}
    <br>
    <textarea placeholder="your answer" key="hello-input" v-on:yo="test">
    	

    </textarea>
    <button @click="toggle=!toggle">compare</button>
    <div v-if="toggle" >
    <pre> {{{currentQuestion.answer}}} {{hoho}}</pre>
    <br>
    <button @click="next(1)">correct?</button>
    <button @click="next(0)">not correct?</button>
    </div>
    </div>
    <div v-if="idInterro == questions.length && idInterro > 0" @save="test">
    your score : {{points}} / {{ questions.length}}
    <button @click="save" :disabled="finishRound">save</button>
    </div>
    <ul><li v-for="s in sessions" >{{s.date}}:{{s.note}}</li></ul>
 </div>
 <script src="http://cdn.jsdelivr.net/vue/1.0.16/vue.js"></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

 

  <script>
    // our VueJs instance bound to the div with an id of vue-instance


    var vm = new Vue({
      el: '#vue-instance',
      data:{
        idInterro:-1,
           idMatiere: <?php echo $id ?>,
        interro:"",
        question:{question:"",answer:""},
        answer:"",
        interros: [],
        questions:[],
        toggle:false,
        points:0,
        sessions:[],
        finishRound :false
      },
      created:function()
        { 
           
          axios.get("web/interros/"+this.idMatiere).then(function (response) 
          {
              vm._data.interros = response.data;
            })

          },
           methods:
      {save:function()
      	{

      		this.idInterro--;
			
				axios.post("web/addSession",
						{idInterro:this.currentQuestion.idInterro,
							score:this.points/this.questions.length}).then(
							response=>console.log(response.data))

							this.idInterro++;
								this.finishRound=true;
							

						
      	},
      	next:function(point)
      	{
      			if(!this.finished)
      				this.idInterro++;
      	
      			console.log(point)
      			this.points+=point;
      			this.toggle = !this.toggle;
      	},
        showQuestion:function(_id)
        {
        	this.finishRound = false;

           axios.get("web/interro/"+_id).then(function (response) 
          {
              vm.questions = response.data;
              vm.idInterro = 0;
              vm.points = 0;
            })
                  axios.get("web/session/"+_id).then(function (response) 
          {
              vm.sessions = response.data;

            })
        }},
        computed:{
        currentQuestion : function()
        {
        	return this.questions[this.idInterro];
        },
        finished:function()
        {
        	out =  (this.idInterro) == this.questions.length && this.idInterro > 0;
  
    	
        	return out;
        }
    }
     });

     </script>
  </body>
  </html>