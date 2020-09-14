<?php

namespace App\Command;

use App\Repository\ProductPersoRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class HandleExpirationCommand extends Command
{
    protected static $defaultName = 'app:product:handleexpiration';

    private $em;
    private $repository;

    public function __construct(EntityManagerInterface $em, ProductPersoRepository $repository)
    {
        parent::__construct();

        $this->em = $em;
        $this->repository = $repository;
    }


    protected function configure()
    {
        $this
            ->setDescription('permet d\'aller chercher les produits dont la date d\'expiration est hier et passent le bool expired à false')
            // ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        // $arg1 = $input->getArgument('arg1');
        
        $productsPersoToExpire0 = $this->repository->findBy([
            'expiration_date' => new \DateTime('-1 days'),
        ]);
        foreach ($productsPersoToExpire0 as $productPersoToExpire) {

            $productPersoToExpire->setExpiresIn(0);
            
        }
        $productsPersoToExpire1 = $this->repository->findBy([
            'expiration_date' => new \DateTime('0 days'),
        ]);

        foreach ($productsPersoToExpire1 as $productPersoToExpire) {

            $productPersoToExpire->setExpiresIn(1);
        }

        $productsPersoToExpire2 = $this->repository->findBy([
            'expiration_date' => new \DateTime(' 1 days'),
        ]);
        foreach ($productsPersoToExpire2 as $productPersoToExpire) {

            $productPersoToExpire->setExpiresIn(2);
        }

        
        $this->em->flush();


        $io->success('Les produits sont bien notés comme expirés');

        return 0;
    }
}
