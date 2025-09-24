class Operaciones {

    constructor(numero1, numero2) {
        this.numero1 = numero1;
        this.numero2 = numero2;
    }
    suma(){
        return this.numero1 + this.numero2;
    }  
}

function  sumarNumeros(){ 
        var n1 =parseInt(document.getElementById("txtNumero1").value) ;
        var n2 =parseInt(document.getElementById("txtNumero2").value) ;
        var objsuma = new Operaciones(n1, n2);
       // alert(objsuma.suma());
        document.getElementById("resultado").innerHTML = objsuma.suma() ;
    }

datos = document.getElementById("resultado");

datos.innerHTML = `<h1>Cuarto Software</h1> 
                     <h3> La altura es: ${altura} </h3> `;
                  

/*if(altura > 170)
{
   datos.innerHTML += "Usted es alto"
}else
{
    datos.innerHTML  += "Usted es bajo"; 
} */

function altura(estatura)
{
    datos.innerHTML = `<h1> cuarto software </h1>
                     <h3> La altura es: ${estatura} </h3> `
if(estatura > 170)
{
   datos.innerHTML += "Usted es alto"
}else
{
    datos.innerHTML  += "Usted es bajo"; 
}
                     return datos;
}

function imprimir(){
    datos = document.getElementById("resultado");
    datos = altura(180);
}

imprimir();

nombre = ["carlos", "andres", "ana"];
/*for (i = 0; i < nombre.length; i++) {
    document.writeln(nombre[i]);
}
*/
nombre.forEach(nombre =>{
    document.writeln(nombre);
});


persona = {
    nombre: "Carlos",
    Apellido: "Nuñez",
    edad: 43,
    educacion: ["primaria", "secundaria"],
    saludo: function (){
         alert(persona.nombre+ " "+ persona.educacion[0]);    
    }
};

persona.saludo();
//alert(persona.nombre+ " "+ persona.educacion[0]);

