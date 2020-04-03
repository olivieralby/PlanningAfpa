import {Flipper} from 'flip-toolkit'
export default class EditStudy{
    constructor(element){
        this.element =element
        this.content = document.querySelector('.study_content')
        this.spinner = document.querySelector('.spinner2')
        
        let links = this.element.querySelectorAll('a')
        links.forEach(element => {
            
           element.addEventListener('click',(e)=>{
               e.preventDefault();
               this.spinner.style.opacity = 1
               let va = 'ol-'+ element.id
               this.content = document.getElementById(va)
               this.loadUrl(element.getAttribute('href'))
           }) 
        });
    }

    async loadUrl(url){
        const response = await fetch(url,{headers:{
            'X-Requested-With':'XMLHttpRequest'
        }})
        const data = await response.json()
        this.spinner.style.opacity = 0
        this.content.innerHTML = data.content
        history.replaceState({},'',url)
        this.close()
    }

    close(){
        let url = "http://localhost:8000/home"
        document.querySelector('.study_close').addEventListener('click',()=>{
            history.replaceState({},'',url)
            this.content.innerHTML = ''
            
        })
    }

    
}