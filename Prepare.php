<?php

    namespace App\Models\NovaPoshta\Prepare;

    use App\Models\NovaPoshta\NovaPoshtaResult;
    use App\Models\NpError;
    use Delivery\NovaPoshta\API\NovaPoshtaApi;

    abstract class Prepare
    {
        /**
         * @var NovaPoshtaApi
         */
        protected $NovaPoshtaApi;

        /**
         * @var array
         */
        protected $data;

        /**
         * @var NovaPoshtaResult
         */
        protected $result;

        /**
         * PrepareResult constructor.
         *
         * @param NovaPoshtaApi $NovaPoshtaApi
         * @param array $data
         *
         * @throws \Exception
         */
        public function __construct(NovaPoshtaApi $NovaPoshtaApi, $data)
        {
            return $this
                ->setNovaPoshtaApi($NovaPoshtaApi)
                ->setData($data)
                ->setResult(new NovaPoshtaResult());
        }

        /**
         * @param NovaPoshtaApi $NovaPoshtaApi
         *
         * @return $this
         */
        protected function setNovaPoshtaApi(NovaPoshtaApi $NovaPoshtaApi)
        {
            $this->NovaPoshtaApi = $NovaPoshtaApi;
            return $this;
        }

        /**
         * @return NovaPoshtaApi
         */
        protected function getNovaPoshtaApi()
        {
            return $this->NovaPoshtaApi;
        }

        /**
         * @param $data
         *
         * @return $this
         */
        protected function setData($data)
        {
            $this->data = $data;
            return $this;
        }

        /**
         * @return array
         */
        protected function getData()
        {
            return $this->data;
        }

        /**
         * @param   array $result
         *
         * @return  $this
         */
        protected function setResult($result)
        {
            $this->result = $result;
            return $this;
        }

        /**
         * @return NovaPoshtaResult
         */
        public function getResult()
        {
            return $this->result;
        }

        /**
         * @param string|integer $errorCode
         *
         * @return  mixed
         */
        protected function getNpError($errorCode)
        {
            return NpError::where('code_np_error', $errorCode);
        }

        /**
         * @param array $errorCodes
         * @param array $errorMessages
         *
         * @return  array
         */
        protected function getNpErrorsMessages()
        {
            $errorCodes     = func_get_arg(0);
            $errorMessages  = func_num_args() > 1 ? func_get_arg(1) : [];

            $count = count($errorCodes);
            $errors = [];
            for ($i = 0; $i < $count; $i++) {
                $np_error = $this->getNpError($errorCodes[$i]);
                if ($np_error->exists()) {
                    $errors[] = $np_error->first()->desc_ru;
                } elseif (array_key_exists($i, $errorMessages)) {
                    $errors[] = $errorMessages[$i];
                }
            }

            return $errors;
        }

        /**
         * @throws \Exception
         */
        protected function throwExceptionDontSuccess()
        {
            throw new \Exception("Success: false\nRequest:\n".json_encode($this->getNovaPoshtaApi()->getRequestLastData())."\nAnswer:\n ".json_encode($this->getData())."\n");
        }

        /**
         * @return NovaPoshtaResult
         */
        abstract public function prepare();
    }
