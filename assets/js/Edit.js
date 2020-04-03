
//Permet d'afficher une pop-up editer avec ajax
export default class Edit{

    constructor(element){
        if(element==null){
            return null
        }
        this.content = document.querySelector('.js-content-dashboard')
        this.spinner = document.querySelector('.spinner')
        this.bindEvents()
    }
    bindEvents(){
        let links = document.querySelectorAll('.edit' )
        links.forEach(link=>{
            link.addEventListener('click',e=>{
                this.spinner.style.opacity = 1
                let val = "edit-"+link.id
                this.content = document.getElementById(val)
                e.preventDefault()
                this.loadUrl(link.getAttribute('href'))
            })
        })
    }
    async loadUrl(url){
        const response = await fetch(url,{
            headers:{
                'X-Requested-with':'XMLHttpRequest'
            }
        })
        
        if(response.status>=200 && response.status < 300){
            const data = await response.json()
            this.content.innerHTML = data.content
            this.spinner.style.opacity = 0
            this.close(url)
        }
        history.replaceState({},'',url)
    }
     
    close(url){
        url = window.location.href
        document.querySelector('.button').addEventListener('click',()=>{

            history.replaceState({},'',url)
            this.content.innerHTML = ""
        })
    }
   

}