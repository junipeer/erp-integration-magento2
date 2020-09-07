<?php
namespace Junipeer\ErpIntegration\Console\Command;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\State as AppState;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class SendCustomer extends Command
{
    const ID_ARG = 'id';

    /** @var  \Junipeer\ErpIntegration\Model\Handler\SendCustomerFactory $sendOrderFactory */
    protected $sendOrderFactory;

    /** @var  \Junipeer\ErpIntegration\Helper\DataFactory $dataFactory */
    protected $helperFactory;

    /** @var \Magento\Framework\App\State $appState */
    protected $appState;

    public function __construct(
        \Junipeer\ErpIntegration\Model\Handler\SendCustomerFactory $sendOrderFactory,
        \Junipeer\ErpIntegration\Helper\DataFactory  $helperFactory,
        AppState $appState = null
    ) {
        $this->appState = $appState ?: ObjectManager::getInstance()->get(AppState::class);
        $this->sendOrderFactory = $sendOrderFactory;
        $this->helperFactory = $helperFactory;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName("junipeer:send:customer")
            ->setDescription("Send Customer to the configured Junipeer integration")
            ->setDefinition(
                [
                    new InputOption(
                        self::ID_ARG,
                        '-id',
                        InputOption::VALUE_REQUIRED,
                        'The Magento customer ID you want to send'
                    )

                ]
            );

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->appState->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);
        } catch (\Exception $e) {

        }

        $id = $input->getOption(self::ID_ARG);
        if (!$id) {
            $output->writeln("<error>Invalid ID.</error>");
            return $this;
        }

        $helper = $this->helperFactory->create();
        if (!$helper->isEnabled() || !$helper->sendCustomers()) {
            $output->writeln("<error>The integration is not enabled or sending customers options in not enabled. Please view the settings in admin.</error>");
            return $this;
        }


        $junipeerCustomerClient = $this->sendOrderFactory->create();

        $output->writeln("<info>Trying to send order with id: " . $id . "</info>");


        try {
            $junipeerCustomerClient->sendCustomer($id);
        } catch (\Exception $e) {
            $output->writeln("<error>Could not send cutsomer to Junipeer. Error: " . $e->getMessage() . "</error>");
            throw $e;
        }

        $output->writeln("<info>Customer sent to Junipeer.</info>");

        return $this;
    }



}
