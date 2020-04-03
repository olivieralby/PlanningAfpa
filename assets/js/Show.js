

export default class Show{
    constructor(element){
        if(element==null){
            return
        }
        console.log(window.location.href)
        this.form = element.querySelector('.js-formulaire')
        this.content = element.querySelector('.js-content')
        this.form.querySelectorAll('input').forEach(input=>{
            input.addEventListener('change',this.loadForm.bind(this));
        })
    }

    async loadForm(){
        const data = new FormData(this.form)
        const url = new URL(this.form.getAttribute('action') || window.location.href)
        const params = new URLSearchParams()
        data.forEach((value,key)=>{
            params.append(key,value)
        })
        return this.loadUrl(url.pathname + '?' + params.toString())
    }

    async loadUrl(url){
        const ajaurl = url +'&ajax=1'
        const response = await fetch(ajaurl,{
            headers:{
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        const data = await response.json()
        this.content.innerHTML = data.content
        history.replaceState({},'',url)

    }
}