<?php
class Chef extends Utilisateur {
	static public $keyFieldsNames = array('login_chef'); // par défaut un seul champ
	public $hasAutoIncrementedKey = false;
	
	/**
	 * Fonction d'affichage de la barre de navigation
	 * Fonction qui permet l'affichage d'une barre de navigation responsive (Bootstrap) avec le nom et le prénom du chef des projet ainsi que l'onglet actif
	 * @author Jérémie
	 * @version 1.0
	 */
	public function afficheNavBar()
	{
		echo "
		<nav>
			<form name='formDeDeconnexion' method='post' action='index.php'>
				<input type='hidden' name='deconnexion' value='deconnexion'>
			</form>
			<div class='nav-wrapper light-blue darken-2'>
				<a href='#'' class='brand-logo'>
					<i class='icon-chef'></i>
					$this->nom_chef $this->prenom_chef
				</a>
				<a href='#' data-activates='mobile-demo' class='button-collapse'>
					<i class='mdi-navigation-menu'></i>
				</a>
				<ul id='nav-mobile' class='right hide-on-med-and-down'>
					<li><a href='message.php'>Message</a></li>
					<li><a href='components.html'>Components</a></li>
					<li><a class='navbar-link' href='javascript:document.formDeDeconnexion.submit();'><span class='icon-off'></span></a></li>
				</ul>
				<ul class='side-nav' id='mobile-demo'>
					<li><a href='message.php'>Message</span></a></li>
					<li><a href='components.html'>Components</a></li>
					<li><a class='navbar-link' href='javascript:document.formDeDeconnexion.submit();'><span class='icon-off'></span></a></li>
				</ul>
			</div>
		</nav>";
	}

	/**
	 * Fonction qui envoie un mail au élève sans projet
	 * Fonction qui permet au chef des projet d'envoyer un mail à tous les étudiants sans projet.
	 * @param 	$subject : sujet du mail
	 * @param	$message : message du mail
	 * @author Jérémie
	 * @version 1.0
	 */
	public function mailToSansProjets($subject, $message)
	{
		$DAOtemporaire = new EtudiantsDAO(MaBD::getInstance());
		$res = $DAOtemporaire->getAllWithoutProjects();
		foreach ($res as $etudiant)
		{
			mail($etudiant->mail_etudiant, $subject, $message);
		}
	}

		public function afficheMail()
	{
		echo "
		<form action='' method='post' >
			<h6>Destinataire</h6>
			<div class='row'>
				<div class='input-field col l4'>
					<select name='no_groupe'>";
						$this->allGroupsToOptions();											
		echo				"
					</select>
				</div>			
    			<div class='input-field col l8'>
					<input type='checkbox' id='groupe' />
    				<label for='groupe'>Groupe</label>
    				<input type='checkbox' id='tuteur' />
    				<label for='tuteur'>Tuteur</label>
    				<input type='checkbox' id='chef' />
    				<label for='chef'>Responsable des projets</label>
				</div>
  			</div>
			<div class='input-field'>
				<label for='sujet'>Sujet</label> <input type='text' name='sujet' id='sujet' required>
			</div>
			<div class='input-field'>
				<label for='message'>Message</label>
				<textarea class='materialize-textarea' name='message' required></textarea>
			</div>
			<div class='input-field'>
				<div class='centre'>
					<button type='submit' name='envoi'class='btn light-blue darken-2'>
						<span class='mdi-communication-email'></span> Envoyer
					</button>
				</div>
			</div>
		</form>
		";
	}

		public function allGroups()
	{
		$res = array();
		$resTemp = array();
		
		$DAOtemporaire = new ProjetsDAO(MaBD::getInstance());
		$DAOtemporaire2 =new GroupesDAO(MaBD::getInstance());
		$projets = $DAOtemporaire->getAll();
		foreach ($projets as $projet)
		{
			$resTemp=$DAOtemporaire2->getAllGroupesOfThisProject($projet->no_projet);
			foreach ($resTemp as $groupe)
			{
				$res[] = $groupe;
			}
		}
		return $res;
	}
	
	public function allGroupsToOptions()
	{
		echo "<option value='sans_projet'>Etudiants Sans Projet</option>";
		$tab = $this->allGroups();
		foreach ($tab as $groupe)
		{
			$groupe->toOption();
		}
	}

}
?>