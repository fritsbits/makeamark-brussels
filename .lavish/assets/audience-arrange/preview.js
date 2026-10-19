const arrow='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14"/></svg>';
const variant=new URLSearchParams(location.search).get('variant')||'A';
const copyKey=new URLSearchParams(location.search).get('copy')||'original';
const copyPairs={original:['Give your creative skills to a local project and meet other makers.','Work with a maker team on one communication, design or digital challenge.'],help:['Share your skills on a local project. Meet other makers.','A maker team brings your communication, design or digital project to life.'],awaited:['Share your skills on a local project. Meet other makers.','Get a maker team to bring your long-awaited project to life.'],short:['Share your skills on a local project. Meet other makers.','Get creative help to bring your project to life.']};
const copies=copyPairs[copyKey]||copyPairs.original;
document.body.className=variant==='B'?'two-lines':variant==='C'?'rows':'shared';
const headings=variant==='B'?['<span class="break-for">For</span><span>Makers</span>','<span>For social</span><span>organisations</span>']:['For makers','For social organisations'];
document.querySelector('main').innerHTML='<section class="pair" aria-label="Take part">'+[
 {heading:headings[0],copy:copies[0],cta:'Maker details'},
 {heading:headings[1],copy:copies[1],cta:'Organisation details'}
].map(x=>'<article class="panel"><h2>'+x.heading+'</h2><p>'+x.copy+'</p><span class="pill">'+x.cta+arrow+'</span></article>').join('')+'</section>';
const report=()=>parent.postMessage({type:'arrange-height',variant,height:Math.ceil(document.body.getBoundingClientRect().height)},'*');
new ResizeObserver(report).observe(document.body);document.fonts.ready.then(report);
addEventListener('message',event=>{if(event.source===parent&&event.data?.type==='arrange-guides')document.body.classList.toggle('guides',event.data.enabled)});
