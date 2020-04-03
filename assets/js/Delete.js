
export default class Delete{

    constructor(element){
        if(element==null){
            return
        }
        this.spinner = document.querySelector('.spinner')
        this.content = document.querySelector('.delete-content')
        let links = document.querySelectorAll('.a-delete ')
        links.forEach(link => {
            link.addEventListener('click',(e)=>{
                e.preventDefault()
                this.spinner.style.opacity = 1
                this.loadUrl(link.getAttribute('href'))
            })
            
        });
        
    }

    async loadUrl(url){
        const response = await fetch(url,
            {
                headers:{
                    'X-Requested-With':'XMLHttpRequest'
                }
            })
        const data = await response.json()
        this.content.innerHTML = data.content
        this.spinner.style.opacity = 0
        //history.replaceState({},'',url)
        this.close()
    }

    close(){
        document.querySelector('.btn-primary').addEventListener('click',()=>{
            this.content.innerHTML = ""
        })
    }
}