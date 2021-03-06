<?php
include '.\includes\class.pdogsb.inc.php';
use PHPUnit\Framework\TestCase;
/**
 * Generated by PHPUnit_SkeletonGenerator on 2020-03-27 at 06:03:59.
 */
class PdoGsbTest extends TestCase {

    /**
     * @var PdoGsb
     */
    protected $object;
    protected $visiteurLogin;
    protected $visiteurMdp;
    protected $visiteurId;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->visiteurLogin = "lvillachane";
        $this->visiteurMdp = "jux7g";
        $this->visiteurId = "a131";
        try {
            $this->object = PdoGsb::getPdoGsb();
            // your test code here
        } catch (\Exception $e) {
            $this->fail('Connexion à la base impossible : '.$e);
        }
    }

    /**
     * Tester la connexion
     * @covers PdoGsb::getPdoGsb
     */
    public function testConnexionPdo() {
        $this->assertInstanceOf(PdoGsb::class, $this->object);
    }
    
    /**
     * Tester la récupération d'info d'un visiteur / comptable, d'après nom d'utilisateur et mot de passe
     * @covers PdoBsb::GetInfoVisiteur
     */
    public function testGetInfoUtilisateur() {
        $visiteur = $this->object->getInfosUtilisateur($this->visiteurLogin, $this->visiteurMdp);
        $this->assertTrue(is_array($visiteur), "Utilisateur ou mdp erroné");
        $this->assertEquals($visiteur['id'], $this->visiteurId, "L'utilisateur trouvé ne correspond pas à l'id recherché.");
        $this->assertEquals($visiteur['type'], 'visiteur', "L'utilisateur trouvé ne correspond pas à l'id recherché.");
    }
}
