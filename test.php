<!DOCTYPE html>
<HTML lang=en dir=ltr data-bs-theme="light" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="robots" content="noindex">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Tools</title>
    <link href='https://tools-static.wmflabs.org/cdnjs/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css' rel='stylesheet' type='text/css'>
    <script src='https://tools-static.wmflabs.org/cdnjs/ajax/libs/jquery/3.7.0/jquery.min.js'></script>
    <script src='https://tools-static.wmflabs.org/cdnjs/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js'></script>
</head>

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once __DIR__ . '/work.php';

use function WpRefs\FixPage\DoChangesToText1;

$lang         = isset($_POST['lang']) ? trim($_POST['lang']) : '';
$text         = isset($_POST['text']) ? trim($_POST['text']) : '';
$mdwiki_revid        = isset($_POST['revid']) ? trim($_POST['revid']) : '';
$sourcetitle  = isset($_POST['sourcetitle']) ? trim($_POST['sourcetitle']) : '';
$title  = isset($_POST['title']) ? trim($_POST['title']) : '';

echo "
    <body>
        <div id='maindiv' class='container'>
            <div class='card'>
                <div class='card-header aligncenter' style='font-weight:bold;'>
                    input infos
                </div>
                <div class='card-body'>
";

// ---
$test_text = <<<TEXT
{{Infobox medical condition
|name             =Rh disease
|synonym          =Rh-hemolytic disease the newborn, Rh (D) disease<ref name=Jack2021/>
|image            =Newborn infant with severe hemolytic disease svg hariadhi.svg
|image_size       =
|alt              =
|caption          =Drawing of [[hydrops fetalis]]
|pronounce        =
|specialty        =[[Paediatrics]], [[haematology]], [[transfusion medicine]]
|symptoms         ='''Baby''': [[Low red blood cells]], [[jaundice]]<ref name=NHS2023/><br>'''Mother''': None<ref name=NHS2023/>
|complications    =[[Stillbirth]], [[hydrops fetalis]], [[intellectual disability]], [[hearing loss]], [[vision loss]]<ref name=NHS2023/><ref name=Jack2021/>
|onset            =Before or shortly after birth<ref name=NHS2023Sym/>
|duration         =
|types            =
|causes           =Exposure of Rh-D negative women to Rh-D positive blood before a subsequent Rh-D positive pregnancy<ref name=NHS2023/>
|risks            =
|diagnosis        =Routine [[prenatal care]]<ref name=NHS2023Diag/>
|differential     =
|prevention       =[[Rho(D) immune globulin]]<ref name=NHS2023/>
|treatment        =[[Blood transfusions]], [[phototherapy]], [[intravenous immunoglobulin]]<ref name=NHS2023/>
|medication       =
|prognosis        =30% risk of severe disability<ref name=Zip2015/>
|frequency        =>350,000 per year<ref name=Zip2015/>
|deaths           =~38% risk of death<ref name=Zip2015/>
}}
'''アカゲザル（Rh）病は新生児溶血性疾患'''（ '''Rh-HDFN''' ）としても知られ、妊婦の[[抗体|抗体が]]胎児の[[赤血球|赤血球を]]破壊することによって発生します。 <ref name="NHS2023">{{Cite web|title=Rhesus disease|url=https://www.nhs.uk/conditions/rhesus-disease/|website=nhs.uk|access-date=19 October 2023|language=en|date=23 October 2017|archive-date=22 February 2023|archive-url=https://web.archive.org/web/20230222174905/https://www.nhs.uk/conditions/rhesus-disease/|url-status=live}} {{Webarchive|url=https://web.archive.org/web/20230222174905/https://www.nhs.uk/conditions/rhesus-disease/|date=22 February 2023}}</ref> <ref name="Jack2021">{{Cite journal|last=Jackson|first=ME|last2=Baker|first2=JM|title=Hemolytic Disease of the Fetus and Newborn: Historical and Current State.|journal=Clinics in laboratory medicine|date=March 2021|volume=41|issue=1|pages=133-151|doi=10.1016/j.cll.2020.10.009|pmid=33494881}}</ref>症状としては一般的に[[貧血|、赤血球数の減少]]と赤ちゃんの[[黄疸|黄疸など]]が挙げられます。 <ref name="NHS2023" />発症は出生前または出生直後に起こる可能性がある。 <ref name="NHS2023Sym">{{Cite web|title=Rhesus disease - Symptoms|url=https://www.nhs.uk/conditions/rhesus-disease/symptoms/|website=nhs.uk|access-date=19 October 2023|language=en|date=23 October 2017|archive-date=20 July 2023|archive-url=https://web.archive.org/web/20230720004500/https://www.nhs.uk/conditions/rhesus-disease/symptoms/|url-status=live}} {{Webarchive|url=https://web.archive.org/web/20230720004500/https://www.nhs.uk/conditions/rhesus-disease/symptoms/|date=20 July 2023}}</ref>合併症としては、[[死産]]、[[胎児水腫]]、[[知的障害]]、聴覚障害、[[視覚障害者|視覚障害]]などがあります。 <ref name="NHS2023" /> <ref name="Jack2021" />

これは[[Rh因子|抗D]]抗体による胎児・新生児溶血性疾患（HDFN）の一種である。 <ref name="Jack2021" />これは、RhD陽性の血液に感作されたRhD陰性の女性がRhD陽性の赤ちゃんを妊娠した場合に発生します。 <ref name="NHS2023" />感作は以前の妊娠中または血液製剤への曝露によって起こる可能性がある。 <ref name="Jack2021" />感作の危険因子としては、母親の[[ABO式血液型|血液型がO型]]であることが挙げられる。 <ref name="Jack2021" />診断は通常、定期的な[[出生前検査|出生前検診]]中に行われます。 <ref name="NHS2023Diag">{{Cite web|title=Rhesus disease - Diagnosis|url=https://www.nhs.uk/conditions/rhesus-disease/diagnosis/|website=nhs.uk|access-date=19 October 2023|language=en|date=23 October 2017|archive-date=24 January 2023|archive-url=https://web.archive.org/web/20230124084927/https://www.nhs.uk/conditions/rhesus-disease/diagnosis/|url-status=live}} {{Webarchive|url=https://web.archive.org/web/20230124084927/https://www.nhs.uk/conditions/rhesus-disease/diagnosis/|date=24 January 2023}}</ref>

RhD陰性の妊婦に[[抗Dヒト免疫グロブリン|Rho(D)免疫グロブリン]]を[[筋肉内注射|注射]]することで、99％の症例で予防できます。 <ref name="NHS2023" /> <ref name="Peg2020">{{Cite journal|last=Pegoraro|first=V|last2=Urbinati|first2=D|last3=Visser|first3=GHA|last4=Di Renzo|first4=GC|last5=Zipursky|first5=A|last6=Stotler|first6=BA|last7=Spitalnik|first7=SL|title=Hemolytic disease of the fetus and newborn due to Rh(D) incompatibility: A preventable disease that still produces significant morbidity and mortality in children.|journal=PloS one|date=2020|volume=15|issue=7|pages=e0235807|doi=10.1371/journal.pone.0235807|pmid=32687543}}</ref>罹患した乳児の治療は重症度に応じて異なり、[[輸血]]、[[光療法|光線療法]]、または[[免疫グロブリン療法|静脈内免疫グロブリン投与]]が必要となる場合がある。 <ref name="NHS2023" /> <ref name="NHS2023Tx" />出生前に赤ちゃんに輸血が行われることもあります。 <ref name="NHS2023Tx">{{Cite web|title=Rhesus disease - Treatment|url=https://www.nhs.uk/conditions/rhesus-disease/treatment/|website=nhs.uk|access-date=19 October 2023|language=en|date=23 October 2017|archive-date=7 March 2023|archive-url=https://web.archive.org/web/20230307051322/https://www.nhs.uk/conditions/rhesus-disease/treatment/|url-status=live}} {{Webarchive|url=https://web.archive.org/web/20230307051322/https://www.nhs.uk/conditions/rhesus-disease/treatment/|date=7 March 2023}}</ref>場合によっては治療を早期に開始するために早期出産が推奨されることもある。 <ref name="NHS2023Tx" />

アカゲザル病は、年間35万人以上の赤ちゃんに影響を与えると推定されています。 <ref name="Zip2015" />[[先進国]]では珍しいが、[[開発途上国|発展途上国]]では比較的よく見られる。 <ref name="NHS2023" /> <ref name="Zip2015">{{Cite journal|last=Zipursky|first=A|last2=Bhutani|first2=VK|title=Impact of Rhesus disease on the global problem of bilirubin-induced neurologic dysfunction.|journal=Seminars in fetal & neonatal medicine|date=February 2015|volume=20|issue=1|pages=2-5|doi=10.1016/j.siny.2014.12.001|pmid=25582277}}</ref>危険な妊娠では、約3分の1の赤ちゃんが死亡し、さらに3分の1に障害が残ります。 <ref name="Zip2015" /> 2020年時点で、予防措置を受けるべき女性の約半数が予防措置を受けていない。 <ref name="Peg2020" />この症状はフランスで少なくとも1609年にはすでに記述されていたが、紀元前400年頃には[[ヒポクラテス]]によっても記述されていた可能性がある。 <ref name="Jack2021" />

== 参考文献 ==
<ref>{{Cite journal|title=Results of clinical trials of RhoGAM in women|journal=Transfusion|volume=8|issue=3|pages=151–153|date=1968-05-06|pmid=4173363|doi=10.1111/j.1537-2995.1968.tb04895.x}}</ref>
<references />
[[Category:Translated from MDWiki]]
TEXT;

// ---
if (empty($lang) || empty($text) || empty($mdwiki_revid) || empty($sourcetitle)) {
    // عرض نموذج لإرسال البيانات إلى text_changes.php
    echo <<<HTML
        <form action='test.php' method='POST'>
            <div class='container'>
                <div class='row'>
                    <div class='col-md-3'>
                        <div class='input-group mb-3'>
                            <div class='input-group-prepend'>
                                <span class='input-group-text'>Langcode</span>
                            </div>
                            <input class='form-control' type='text' name='lang' id='lang' value='ja' required />
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class='input-group mb-3'>
                            <div class='input-group-prepend'>
                                <span class='input-group-text'>title</span>
                            </div>
                            <input class='form-control' type='text' id='title' name='title' value='利用者:Doc James/Rh血液型不適合' />
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class='input-group mb-3'>
                            <div class='input-group-prepend'>
                                <span class='input-group-text'>sourcetitle</span>
                            </div>
                            <input class='form-control' type='text' id='sourcetitle' name='sourcetitle' value='Rhesus disease' required />
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class='input-group mb-3'>
                            <div class='input-group-prepend'>
                                <span class='input-group-text'>revid</span>
                            </div>
                            <input class='form-control' type='text' id='revid' name='revid' value='1457313' required />
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-md-3'>
                        <div class='input-group mb-3'>
                            <div class='input-group-prepend'>
                                <span class='input-group-text'>test</span>
                            </div>
                            <input class='form-control' type='text' id='test' name='test' value='1' />
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <h4 class='aligncenter'>
                            <input class='btn btn-outline-primary' type='submit' value='start'>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="text" class="form-label">Text:</label>
                <textarea id="text" name="text" rows="5" class="form-control" required>$test_text</textarea>
            </div>
        </form>
    HTML;
} else {
    // استدعاء الدالة التي تجري التعديلات على النص
    $new_text = DoChangesToText1($sourcetitle, $title, $text, $lang, $mdwiki_revid);
    $new_text_sanitized = htmlspecialchars($new_text, ENT_QUOTES, 'UTF-8');
    $no_changes = trim($new_text) === trim($text);
    echo <<<HTML
    <h2>New Text: (no_changes: $no_changes)</h2>
        <textarea name="new_text" rows="15" cols="140">$new_text_sanitized</textarea>
    HTML;
}
// ---
echo <<<HTML
                </div>
            </div>
        </div>
    </body>
</html>
HTML;

?>
