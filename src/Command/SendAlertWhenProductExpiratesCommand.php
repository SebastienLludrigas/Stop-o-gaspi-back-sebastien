<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Repository\ProductPersoRepository;

use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;


class SendAlertWhenProductExpiratesCommand extends Command
{
    protected static $defaultName = 'app:product:sendalert';

    private $userRepository;
    private $productPersoRepository;
    private $em;
    private $mailer;
    private $serializer;

    public function __construct(EntityManagerInterface $em, ProductPersoRepository $productPersoRepository, UserRepository $userRepository, \Swift_Mailer $mailer, SerializerInterface $serializer)
    {
        parent::__construct();

        $this->serializer = $serializer;
        $this->mailer = $mailer;
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->productPersoRepository = $productPersoRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Envoie un mail quand le produit arrive à la veille de sa péremption')
            // ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        // $arg1 = $input->getArgument('arg1');

        $products = $this->productPersoRepository->findAll();

        $listOfProductsToAlerte = [];

        foreach ($products as $productPerso) {

            // on récupère l'utilisateur du produit en cours
            $userToAlert = $productPerso->getUser();

            $expirationDate = $productPerso->getExpirationDate();

            $expirationDateFormatted =  $expirationDate->format('Y-m-d');

            $dayOfAlert = $userToAlert->getAlertDay();

            $dateToAlertFormatted= (new \DateTime("+ $dayOfAlert days"))->format('Y-m-d');

            

            if ($expirationDateFormatted == $dateToAlertFormatted) {

               $listOfProductsToAlerte[$userToAlert->getId()][] = $productPerso;

            }
            // dump($todayFormatted);
            // dd($expirationDate == $todayFormatted);
            //$userToAlert->getEmail(); 
        }
        // dd($listOfProductsToAlerte);
        foreach ($listOfProductsToAlerte as $arrayofProduts) {
            $listTosend = [];
            foreach ($arrayofProduts as $productToAlert) {


                $nameOfProduct = $productToAlert->getName();
                $listTosend[] = $nameOfProduct;
            }
            $user = $this->userRepository->find($productToAlert->getUser()->getId());
            
            $body = 'Coucou Gaspi hunter ' . $user->getName() . ' Alerte: les produits suivants périment demain:' . implode(", ", $listTosend) . ' Pense à les consommer';
            dump($user->getEmail());
            $message = (new \Swift_Message('Stop O Gaspi : Alerte péremption. Fais gaffe: ' . $user->getName()))
                ->setFrom('stopogaspi.oclock@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $body,
                );
            $this->mailer->send($message);
        }

        $io->success('Le mail a bien été envoyé');

        return 0;
    }
}
