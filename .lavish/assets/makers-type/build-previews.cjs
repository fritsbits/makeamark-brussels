const {chromium}=require('playwright');
const fs=require('node:fs');const path=require('node:path');const sharp=require('sharp');
const out=__dirname;const base='https://makeamark-southern-ocean.test';
const css=`
@font-face{font-family:"Jakarta Type Review";src:url("REVIEW_LIGHT");font-weight:300;font-display:swap}
@font-face{font-family:"Jakarta Type Review";src:url("REVIEW_REGULAR");font-weight:400;font-display:swap}
@font-face{font-family:"Jakarta Type Review";src:url("REVIEW_BOLD");font-weight:600;font-display:swap}
.type-proposal .makers-page :is(.audience-hero__intro,.commitment__lead p,.commitment__steps p,.faq>p,.application__context){font-family:"Jakarta Type Review",sans-serif;font-weight:300;font-size:1.0625rem;line-height:1.6}
.type-proposal .makers-page strong{font-family:"Jakarta Type Review",sans-serif;font-weight:600}
.type-proposal .makers-page .back-link{display:inline-flex;align-items:center;justify-self:start;gap:8px;min-height:44px;margin:0 0 12px;font-size:.875rem;font-weight:400;line-height:1.4;text-decoration:none}
.type-proposal .makers-page .back-link svg{width:16px;height:16px;flex:none}
.type-proposal .makers-page .back-link:hover{text-decoration:underline;text-underline-offset:4px}
.type-proposal .makers-page [data-flux-label]{font-size:1rem;line-height:1.5;font-weight:600}
.type-proposal .makers-page :is(.application__privacy-note,.application-form__hint,[data-flux-error],.application-form__required){font-size:.875rem;line-height:1.6;font-weight:400}
.type-proposal .makers-page .audience-hero>.type-date{font-size:.875rem;line-height:1.6;font-weight:400}
.type-proposal .makers-page .commitment__lead h2{text-wrap:balance;max-width:11ch}
.type-proposal .makers-page .application__title{font-size:clamp(2.5rem,5vw,4.25rem);line-height:.98;text-wrap:balance}
`;
const targets={T01:'.commitment',T02:'.audience-hero',T03:'.audience-hero',T04:'.application-form__group:nth-of-type(1)',T05:'.audience-hero',T06:'#application'};
async function capture(page,selector,name,height=680){const bytes=await page.locator(selector).first().screenshot();const meta=await sharp(bytes).metadata();await sharp(bytes).extract({left:0,top:0,width:meta.width,height:Math.min(height,meta.height)}).toFile(path.join(out,name))}
(async()=>{
 fs.mkdirSync(path.join(out,'sources'),{recursive:true});
 for(const [name,file]of Object.entries({css:'resources/css/app.css',makers:'resources/css/makers.css',tokens:'resources/css/tokens.css',fonts:'resources/css/fonts.css',design:'DESIGN.md',audience:'resources/views/audience.blade.php'}))fs.copyFileSync(file,path.join(out,'sources',name+'.txt'));
 for(const file of fs.readdirSync('public/fonts').filter(n=>n.endsWith('.woff2')))fs.copyFileSync(path.join('public/fonts',file),path.join(out,'fonts',file));
 fs.copyFileSync('.bloom/attachments/kVupiV/Pasted 2026-10-18 at 22.07.40.png',path.join(out,'owner-reference.png'));
 const browser=await chromium.launch();const observations=[];
 try{
  for(const[device,width]of[['desktop',1440],['mobile',390]]){
   const context=await browser.newContext({ignoreHTTPSErrors:true,viewport:{width,height:1000}});const page=await context.newPage();
   const light=fs.readFileSync(path.join(out,'fonts/PlusJakartaSans-Light-300.woff2')).toString('base64');
   const regular=fs.readFileSync(path.join(out,'fonts/23310fc1bc6aca45.woff2')).toString('base64');
   const bold=fs.readFileSync(path.join(out,'fonts/d5911ef167280b45.woff2')).toString('base64');
   await page.goto(base+'/makers');await page.waitForLoadState('networkidle');await page.evaluate(()=>document.fonts.ready);
   observations.push({device,current:await page.evaluate(()=>['.back-link','.audience-hero__intro','.commitment__lead p','[data-flux-label]','.application__privacy-note'].map(selector=>{const el=document.querySelector(selector),c=getComputedStyle(el);return{selector,size:c.fontSize,weight:c.fontWeight,line:c.lineHeight}}))});
   await page.locator('.audience-hero').screenshot({path:path.join(out,device+'-current-hero.png')});
   for(const[id,selector]of Object.entries(targets))await capture(page,selector,`${id}-${device}-current.png`);
   await page.evaluate(()=>{
    document.body.classList.add('type-proposal');
    const link=document.querySelector('.audience-hero .back-link');link.setAttribute('aria-label','Back to the edition');
    link.innerHTML='<svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M11.25 4.75 5.75 10l5.5 5.25M6 10h10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg><span>Edition</span>';
    const date=document.querySelector('.audience-hero>p:not(.audience-hero__intro)');date.classList.add('type-date');
    const replacements=[['.audience-hero__intro','local project'],['.commitment__lead p','a full day'],['.commitment__steps article:nth-child(1) p','skills'],['.commitment__steps article:nth-child(2) p','does not guarantee selection'],['.commitment__steps article:nth-child(3) p','present the work']];
    for(const[selector,phrase]of replacements){const el=document.querySelector(selector);el.innerHTML=el.innerHTML.replace(phrase,'<strong>'+phrase+'</strong>')}
   });
   await page.addStyleTag({content:css.replace('REVIEW_LIGHT','data:font/woff2;base64,'+light).replace('REVIEW_REGULAR','data:font/woff2;base64,'+regular).replace('REVIEW_BOLD','data:font/woff2;base64,'+bold)});
   await page.evaluate(()=>document.fonts.ready);
   observations.at(-1).proposed=await page.evaluate(()=>({overflow:document.documentElement.scrollWidth-innerWidth,bodyWeight:getComputedStyle(document.querySelector('.commitment__lead p')).fontWeight,emphasisWeight:getComputedStyle(document.querySelector('.commitment__lead strong')).fontWeight,backTarget:document.querySelector('.back-link').getBoundingClientRect().toJSON()}));
   if(observations.at(-1).proposed.overflow!==0)throw new Error('proposal overflow '+device);
   for(const[id,selector]of Object.entries(targets))await capture(page,selector,`${id}-${device}-proposed.png`);
   await page.locator('.audience-hero').screenshot({path:path.join(out,device+'-proposed-hero.png')});
   await page.screenshot({path:path.join(out,device+'-proposed-full.png'),fullPage:true});
   await context.close();
  }
 }finally{await browser.close()}
 fs.writeFileSync(path.join(out,'observations.json'),JSON.stringify(observations,null,2));console.log(JSON.stringify(observations));
})();
