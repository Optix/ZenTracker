<?php

/**
 * sondages actions.
 *
 * @package    zt2
 * @subpackage sondages
 * @author     Optix
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sondagesActions extends sfActions {
 
 /**
  * Liste des sondages
  */
  public function executeIndex(sfWebRequest $r) {
    // On récupère les sondages
    $this->sdg = Doctrine_Query::create()
      ->select('s.*, COUNT(v.idreponse) as cnt, MAX(v.creation) AS lastvote')
      ->from('SdgQuestions s')
      ->leftJoin("s.SdgReponses r")
      ->leftJoin("r.SdgVotes v")
      ->groupBy('s.idsdg_questions')
      ->orderBy('s.idsdg_questions DESC')
      ->useQueryCache(true)->setQueryCacheLifeSpan(3600*24)
      ->execute(array(), Doctrine::HYDRATE_ARRAY);
  }
  
 /**
  * Voir un sondage
  */
  public function executeVoir(sfWebRequest $r) {
    $cnt = 0;
    $v = array();
    // On récupère le sondage
    $s = Doctrine_Query::create()
      ->select('s.*, r.*, v.*')
      ->from('SdgQuestions s')
      ->leftJoin('s.SdgReponses r')
      ->leftJoin('r.SdgVotes v')
      ->where('s.idsdg_questions = ?', $r->getUrlParameter("id"))
      ->useQueryCache(true)->setQueryCacheLifeSpan(3600*24)
      ->execute();
    // On récupère le nbre de votes total
    foreach ($s[0]['SdgReponses'] as $rep) {
      $cnt += count($rep['SdgVotes']);
    }
    // On récupère les votants
    foreach ($s[0]['SdgReponses'] as $rep) {
      // Tous ceux qui ont votés
      foreach ($rep['SdgVotes'] as $a) {
        // Si notre ID est dedans, alors c'est qu'on a déjà voté ;) 
        if (isset($a['Membre']['mbr_id']) && $a['Membre']['mbr_id'] == $this->getUser()->getAttribute("id"))
          $a_vote = true;
        $v[] = $a['Membre'];
      }
    }
    if (!isset($a_vote))
      $a_vote = false;
    // Injection dans le template
    $this->sdg = $s[0]; // Sondage (arbre)
    $this->cnt = $cnt;
    $this->vot = $v;
    $this->avote = $a_vote;
  }
  
 /**
  * Répondre à un sondage
  */
  public function executeVoter(sfWebRequest $r) {
    if (!$r->isMethod('post'))
      $this->forward404();
    // Enregistrement
    $v = new SdgVotes();
    $v->setIdreponse($r->getPostParameter("rep"));
    $v->setMid($this->getUser()->getAttribute("id"));
    $v->setCreation(date("Y-m-d H:i:s"));
    $v->save();
    // Url du sondage
    $sdg = Doctrine::getTable("SdgReponses")->find($r->getPostParameter("rep"));
    $url = $this->getController()->genUrl('sondages/voir?id='.$sdg->getIdquest());
    $this->getUser()->setFlash("notice", $this->getContext()->getI18N()->__("Thanks. I will redirect you."));
    $this->redirect("sondages/index#".$url);
  }
  
 /**
  * Créer un nouveau sondage
  */
  public function executeNouveau(sfWebRequest $r) {
    if ($r->isMethod('post')) {    
      // On a assez de réponses ?
      $rep = explode("\r\n", $r->getPostParameter("txt"));
      if (count($rep) <= 1) {
        $this->getUser()->setFlash("error", $this->getContext()->getI18N()->__('I need at least 2 answers.'));
        $this->redirect("sondages/index");
      }

      // Et le titre ?
      $titre = trim($r->getPostParameter("titre"));
      if (strlen($titre) <= 3) {
        $this->getUser()->setFlash("error", $this->getContext()->getI18N()->__("The title seems too short for me."));
        $this->redirect("sondages/index");
      }

      // On enregistre la question
      $q = new SdgQuestions();
      $q->setQuestion($titre);
      $q->setCreation(date("Y-m-d H:i:s"));
      $q->save();

      // On enregistre les réponses
      foreach ($rep as $r) {
        if (strlen($r) === 0) continue;
        $c = new SdgReponses();
        $c->setIdquest($q->getIdsdgQuestions());
        $c->setReponse($r);
        $c->save();
      }

      // On écrit ça dans la shout
      $s = Doctrine::getTable("Shoutbox")->ecrireShout(array(
        "img" => "chart_bar_add",
        "msg" => $this->getContext()->getI18N()->__("has just created a new poll !"),
        "url" => $this->getController()->genUrl('sondages/voir?id='.$q->getIdsdgQuestions()),
        "titre" => $titre,
      ));
      // Confirmation
      $this->getUser()->setFlash("notice", $this->getContext()->getI18N()->__("Your poll has just been created. Go vote !"));
      // Url du sondage
      $url = $this->getController()->genUrl('sondages/voir?id='.$q->getIdsdgQuestions());
      // Redirection
      $this->redirect("sondages/index#".$url);
    }
  }
  
 /**
  * Supprimer un sondage
  */
  public function executeSupprimer(sfWebRequest $r) {
    $s = Doctrine::getTable("SdgQuestions")->find($r->getUrlParameter("id"))->delete();
    $this->getUser()->setFlash("notice", $this->getContext()->getI18N()->__("The poll has been deleted."));
    $this->redirect("sondages/index");
  }
}
